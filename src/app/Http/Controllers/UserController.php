<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /* Authorization */
    /* -------------------------------------------------------------------------- */
    private function ensureAdministrator(): void
    {
        abort_unless(
            auth()->user()?->isAdministrator(),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }
    /* -------------------------------------------------------------------------- */

    /* Helper */
    /* -------------------------------------------------------------------------- */
    private function ensureUserAccessible(User $user): void
    {
        abort_if(
            $user->isSuperAdmin() && ! auth()->user()?->isSuperAdmin(),
            403,
            'Anda tidak memiliki akses untuk mengelola akun Super Admin.'
        );
    }

    private function ensureUserNotProtected(User $user): void
    {
        $this->ensureUserAccessible($user);

        abort_if(
            $user->is_protected,
            403,
            'Akun Super Admin utama tidak dapat diubah melalui aplikasi.'
        );
    }

    private function roles(): array
    {
        $roles = ['administrator', 'canteen_manager', 'tenant', 'tenant_employee'];

        if (auth()->user()?->isSuperAdmin()) {
            array_unshift($roles, 'super_admin');
        }

        return $roles;
    }

    private function tenantValidationRules(Request $request, ?User $user = null): array
    {
        $rules = ['exclude_unless:role,tenant,tenant_employee', 'required', 'uuid',
            Rule::exists('tenants', 'id')
                ->where('is_active', true)
                ->whereNull('deleted_at'),
        ];

        // Satu tenant hanya boleh memiliki satu Kepala Tenant.
        if ($request->input('role') === 'tenant') {
            $uniqueRule = Rule::unique('users', 'tenant_id')
                ->where(fn ($query) => $query
                    ->where('role', 'tenant')
                    ->whereNull('deleted_at'));

            if ($user) {
                $uniqueRule->ignore($user->id);
            }

            $rules[] = $uniqueRule;
        }

        return $rules;
    }

    /* User Selection */
    /* -------------------------------------------------------------------------- */
    private function selectUser(Request $request, bool $onlyTrashed = false): User
    {
        $validated = $request->validate([
            'user_id' => ['required', 'uuid', 'exists:users,id'],
        ]);

        $query = $onlyTrashed
            ? User::onlyTrashed()
            : User::query();

        return $query->findOrFail($validated['user_id']);
    }

    private function selectedUser(): User
    {
        $userId = session('users.selected_user_id');

        abort_unless($userId, 404, 'Pengguna belum dipilih.');

        return User::findOrFail($userId);
    }
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    /* Index, Search & Pagination */
    /* -------------------------------------------------------------------------- */
    public function index(Request $request)
    {
        $this->ensureAdministrator();

        $users = User::query()
            ->when(
                ! auth()->user()->isSuperAdmin(),
                fn ($query) => $query->where('role', '!=', 'super_admin')
            )
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($request->input('role'), function ($query, $role) {
                $query->where('role', $role);
            })
            ->when(
                $request->input('status') !== null &&
                $request->input('status') !== '',
                function ($query) use ($request) {
                    $query->where('is_active', $request->input('status'));
                }
            )
            ->latest()
            ->paginate(10, ['*'], 'page', max(1, (int) $request->input('page', 1)));

        $data = [
            'users' => $users,
            'roles' => $this->roles(),
        ];

        if ($request->ajax()) {
            return view('users.partials.table', compact('users'));
        }

        return view('users.index', $data);
    }
    /* -------------------------------------------------------------------------- */

    /* Create User */
    /* -------------------------------------------------------------------------- */
    public function create()
    {
        $this->ensureAdministrator();

        return view('users.create', [
            'roles' => $this->roles(),
            'tenants' => Tenant::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'code', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'regex:/\A[a-z0-9_-]+\z/', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($this->roles())],
            'tenant_id' => $this->tenantValidationRules($request),
        ]);

        // Simpan tenant_id secara eksplisit.
        $tenantId = $validated['tenant_id'] ?? null;
        unset($validated['tenant_id']);

        $validated['is_active'] = true;
        $validated['is_protected'] = false;

        $user = User::create($validated);
        $user->tenant_id = $tenantId;
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }
    /* -------------------------------------------------------------------------- */

    /* User Detail */
    /* -------------------------------------------------------------------------- */
    public function selectDetail(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request);
        $this->ensureUserAccessible($user);
        session()->put('users.selected_user_id', $user->id);

        return redirect()->route('users.show');
    }

    public function show()
    {
        $this->ensureAdministrator();
        $user = $this->selectedUser();
        $this->ensureUserAccessible($user);

        return view('users.show', compact('user'));
    }
    /* -------------------------------------------------------------------------- */

    /* Edit User */
    /* -------------------------------------------------------------------------- */
    public function selectEdit(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request);
        $this->ensureUserNotProtected($user);
        session()->put('users.selected_user_id', $user->id);

        return redirect()->route('users.edit');
    }

    public function edit()
    {
        $this->ensureAdministrator();
        $user = $this->selectedUser();
        $this->ensureUserNotProtected($user);

        return view('users.edit', [
            'user' => $user,
            'roles' => $this->roles(),
            'tenants' => Tenant::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'code', 'name']),
        ]);
    }

    public function update(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectedUser();
        $this->ensureUserNotProtected($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'regex:/\A[a-z0-9_-]+\z/', 'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($this->roles())],
            'tenant_id' => $this->tenantValidationRules($request, $user),
        ]);

        $tenantId = $validated['tenant_id'] ?? null;
        unset($validated['tenant_id']);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Lindungi Administrator aktif terakhir.
        if ($user->is_active && $user->role === 'administrator' && $validated['role'] !== 'administrator' &&
            User::where('role', 'administrator')
                ->where('is_active', true)
                ->count() <= 1
        ) {
            return back()
                ->withInput()
                ->withErrors(['role' => 'Tidak dapat mengubah role Administrator aktif terakhir.']);
        }

        $user->fill($validated);
        $user->tenant_id = $tenantId;
        $user->save();

        return redirect()
            ->route('users.show')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
    /* -------------------------------------------------------------------------- */

    /* User Status */
    /* -------------------------------------------------------------------------- */
    public function toggleStatus(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request);
        $this->ensureUserNotProtected($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        // Lindungi Administrator aktif terakhir dari Administrator biasa.
        // Super Admin tetap boleh menonaktifkannya.
        if (! auth()->user()->isSuperAdmin() && $user->is_active && $user->role === 'administrator' &&
            User::where('role', 'administrator')
                ->where('is_active', true)
                ->count() <= 1
        ) {
            return back()
                ->with('error', 'Administrator aktif terakhir tidak dapat dinonaktifkan.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $message = $user->is_active
            ? 'Akun pengguna berhasil diaktifkan.'
            : 'Akun pengguna berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }
    /* -------------------------------------------------------------------------- */

    /* Soft Delete */
    /* -------------------------------------------------------------------------- */
    public function destroy(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request);
        $this->ensureUserNotProtected($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->is_active) {
            return back()->with('error', 'Nonaktifkan akun terlebih dahulu sebelum menghapusnya.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
    /* -------------------------------------------------------------------------- */

    /* Trash, Restore & Force Delete */
    /* -------------------------------------------------------------------------- */
    public function trash()
    {
        $this->ensureAdministrator();

        $users = User::onlyTrashed()
            ->when(
                ! auth()->user()->isSuperAdmin(),
                fn ($query) => $query->where('role', '!=', 'super_admin')
            )
            ->latest('deleted_at')
            ->paginate(10);

        return view('users.trash', compact('users'));
    }

    public function restore(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request, true);
        $this->ensureUserNotProtected($user);
        $user->restore();

        return redirect()
            ->route('users.trash')
            ->with('success', 'Akun pengguna berhasil dipulihkan.');
    }

    public function forceDelete(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request, true);
        $this->ensureUserNotProtected($user);
        $userId = $user->id;
        $user->forceDelete();

        // Bersihkan session jika pengguna yang dihapus
        // masih tersimpan sebagai pengguna terpilih.
        if (session('users.selected_user_id') === $userId) {
            session()->forget('users.selected_user_id');
        }

        if (session('users.reset_password_user_id') === $userId) {
            session()->forget('users.reset_password_user_id');
        }

        return redirect()
            ->route('users.trash')
            ->with('success', 'Akun pengguna berhasil dihapus permanen.');
    }
    /* -------------------------------------------------------------------------- */

    /* Reset Password */
    /* -------------------------------------------------------------------------- */
    public function startPasswordReset(Request $request)
    {
        $this->ensureAdministrator();
        $user = $this->selectUser($request);
        $this->ensureUserNotProtected($user);

        // Simpan target reset password.
        session()->put('users.reset_password_user_id', $user->id);

        // Agar setelah reset dapat kembali ke detail pengguna yang sama.
        session()->put('users.selected_user_id', $user->id);

        return redirect()->route('users.resetpassword.edit');
    }

    public function editPassword()
    {
        $this->ensureAdministrator();
        $userId = session('users.reset_password_user_id');
        abort_unless($userId, 404, 'Target Reset Password tidak ditemukan.');
        $user = User::findOrFail($userId);
        $this->ensureUserNotProtected($user);

        return view('users.resetpassword', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $this->ensureAdministrator();
        $userId = session('users.reset_password_user_id');
        abort_unless($userId, 404, 'Target Reset Password tidak ditemukan.');
        $user = User::findOrFail($userId);
        $this->ensureUserNotProtected($user);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        session()->forget('users.reset_password_user_id');

        return redirect()
            ->route('users.show')
            ->with('success', 'Password pengguna berhasil diperbarui.');
    }
    /* -------------------------------------------------------------------------- */
}
