<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
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

    /* Automated Generate Tenant Code */
    /* -------------------------------------------------------------------------- */
    private function generateTenantCode(): string
    {
        do {
            $code = 'TN-'.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Tenant::where('code', $code)->exists());

        return $code;
    }
    /* -------------------------------------------------------------------------- */

    /* Tenant Selection */
    /* -------------------------------------------------------------------------- */
    private function selectTenant(Request $request): Tenant
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'uuid', 'exists:tenants,id'],
        ]);

        return Tenant::findOrFail($validated['tenant_id']);
    }

    private function selectedTenant(): Tenant
    {
        $tenantId = session('tenants.selected_tenant_id');
        abort_unless($tenantId, 404, 'Tenant belum dipilih.');

        return Tenant::findOrFail($tenantId);
    }
    /* -------------------------------------------------------------------------- */

    /* Index, Search & Pagination */
    /* -------------------------------------------------------------------------- */
    public function index(Request $request)
    {
        $this->ensureAdministrator();

        $tenants = Tenant::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when(
                $request->input('status') !== null &&
                $request->input('status') !== '',
                function ($query) use ($request) {
                    $query->where('is_active', $request->input('status'));
                }
            )
            ->withCount('users')
            ->latest()
            ->paginate(10, ['*'], 'page', max(1, (int) $request->input('page', 1)));

        if ($request->ajax()) {
            return view(
                'tenants.partials.table',
                compact('tenants')
            );
        }

        return view('tenants.index', compact('tenants'));
    }
    /* -------------------------------------------------------------------------- */

    /* Create Tenant */
    /* -------------------------------------------------------------------------- */
    public function create()
    {
        $this->ensureAdministrator();

        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['code'] = $this->generateTenantCode();
        $validated['is_active'] = true;

        Tenant::create($validated);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Tenant berhasil ditambahkan.');
    }
    /* -------------------------------------------------------------------------- */

    /* Tenant Detail */
    /* -------------------------------------------------------------------------- */
    public function selectDetail(Request $request)
    {
        $this->ensureAdministrator();
        $tenant = $this->selectTenant($request);
        session()->put('tenants.selected_tenant_id', $tenant->id);

        return redirect()->route('tenants.show');
    }

    public function show()
    {
        $this->ensureAdministrator();
        $tenant = $this->selectedTenant();
        $tenant->load(['head', 'employees']);
        $tenant->loadCount('users');

        return view('tenants.show', compact('tenant'));
    }
    /* -------------------------------------------------------------------------- */

    /* Edit Tenant */
    /* -------------------------------------------------------------------------- */
    public function selectEdit(Request $request)
    {
        $this->ensureAdministrator();
        $tenant = $this->selectTenant($request);
        session()->put('tenants.selected_tenant_id', $tenant->id);

        return redirect()->route('tenants.edit');
    }

    public function edit()
    {
        $this->ensureAdministrator();
        $tenant = $this->selectedTenant();

        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request)
    {
        $this->ensureAdministrator();
        $tenant = $this->selectedTenant();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $tenant->update($validated);

        return redirect()
            ->route('tenants.show')
            ->with('success', 'Data tenant berhasil diperbarui.');
    }
    /* -------------------------------------------------------------------------- */

    /* Tenant Status */
    /* -------------------------------------------------------------------------- */
    public function toggleStatus(Request $request)
    {
        $this->ensureAdministrator();
        $tenant = $this->selectTenant($request);
        $tenant->update(['is_active' => ! $tenant->is_active]);

        $message = $tenant->is_active
            ? 'Tenant berhasil diaktifkan.'
            : 'Tenant berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }
    /* -------------------------------------------------------------------------- */

    /* Soft Delete */
    /* -------------------------------------------------------------------------- */
    public function destroy(Request $request)
    {
        $this->ensureAdministrator();
        $tenant = $this->selectTenant($request);

        // Tenant yang masih memiliki kepala & pegawai tidak boleh dihapus.
        if (User::withTrashed()->where('tenant_id', $tenant->id)->exists()) {
            return back()->with(
                'error', 'Tenant tidak dapat dihapus karena masih memiliki pengguna terkait.'
            );
        }

        $tenant->delete();

        if (
            session('tenants.selected_tenant_id') === $tenant->id
        ) {
            session()->forget('tenants.selected_tenant_id');
        }

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Tenant berhasil dihapus.');
    }
    /* -------------------------------------------------------------------------- */
}
