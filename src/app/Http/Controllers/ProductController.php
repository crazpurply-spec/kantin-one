<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /* Authorization */
    /* -------------------------------------------------------------------------- */

    private function currentUser()
    {
        return auth()->user();
    }

    private function ensureTenantAccess(): void
    {
        $user = $this->currentUser();

        abort_unless(
            $user && (
                $user->isAdministrator() ||
                $user->isTenant() ||
                $user->role === 'tenant_employee'
            ),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        if (! $user->isAdministrator()) {
            abort_unless(
                $user->tenant_id,
                403,
                'Akun Anda belum terhubung dengan tenant.'
            );
        }
    }

    private function ensureTenantManager(): void
    {
        $user = $this->currentUser();

        abort_unless(
            $user && (
                $user->isAdministrator() ||
                $user->isTenant()
            ),
            403,
            'Anda tidak memiliki akses untuk mengelola produk.'
        );
    }

    /* -------------------------------------------------------------------------- */

    /* Helper */
    /* -------------------------------------------------------------------------- */

    private function generateProductCode(): string
    {
        do {
            $code = 'PR-'.str_pad(
                (string) random_int(0, 999999),
                6,
                '0',
                STR_PAD_LEFT
            );
        } while (Product::where('code', $code)->exists());

        return $code;
    }

    private function productQuery()
    {
        $user = $this->currentUser();

        $query = Product::query()->with('tenant');

        // Tenant hanya dapat mengakses produk miliknya sendiri.
        if (! $user->isAdministrator()) {
            $query->where('tenant_id', $user->tenant_id);
        }

        return $query;
    }

    private function selectProduct(Request $request): Product
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'uuid',
                'exists:products,id',
            ],
        ]);

        return $this->productQuery()
            ->findOrFail($validated['product_id']);
    }

    private function selectedProduct(): Product
    {
        $productId = session('products.selected_product_id');

        abort_unless($productId, 404, 'Produk belum dipilih.');

        return $this->productQuery()->findOrFail($productId);
    }

    private function tenantOptions()
    {
        return Tenant::query()
            ->orderBy('name')
            ->get(['id', 'code', 'name']);
    }

    /* -------------------------------------------------------------------------- */

    /* Index, Search & Pagination */
    /* -------------------------------------------------------------------------- */

    public function index(Request $request)
    {
        $this->ensureTenantAccess();

        $user = $this->currentUser();

        $products = $this->productQuery()
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(
                $user->isAdministrator() &&
                $request->filled('tenant_id'),
                function ($query) use ($request) {
                    $query->where('tenant_id', $request->input('tenant_id'));
                }
            )
            ->when(
                $request->input('status') !== null &&
                $request->input('status') !== '',
                function ($query) use ($request) {
                    $query->where(
                        'is_active',
                        $request->input('status')
                    );
                }
            )
            ->latest()
            ->paginate(
                10,
                ['*'],
                'page',
                max(1, (int) $request->input('page', 1))
            );

        if ($request->ajax()) {
            return view('products.partials.table', compact('products'));
        }

        $tenants = $user->isAdministrator()
            ? $this->tenantOptions()
            : collect();

        return view('products.index', compact('products', 'tenants'));
    }

    /* -------------------------------------------------------------------------- */

    /* Create Product */
    /* -------------------------------------------------------------------------- */

    public function create()
    {
        $this->ensureTenantManager();

        $user = $this->currentUser();

        $tenants = $user->isAdministrator()
            ? $this->tenantOptions()
            : collect();

        return view('products.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $this->ensureTenantManager();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if (auth()->user()->isAdministrator()) {
            $rules['tenant_id'] = ['required', 'uuid', 'exists:tenants,id'];
        }

        $validated = $request->validate($rules);

        $tenantId = auth()->user()->isAdministrator()
            ? $validated['tenant_id']
            : auth()->user()->tenant_id;

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create([
            'tenant_id' => $tenantId,
            'code' => $this->generateProductCode(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /* -------------------------------------------------------------------------- */

    /* Product Detail */
    /* -------------------------------------------------------------------------- */

    public function selectDetail(Request $request)
    {
        $this->ensureTenantAccess();

        $product = $this->selectProduct($request);

        session()->put('products.selected_product_id', $product->id);

        return redirect()->route('products.show');
    }

    public function show()
    {
        $this->ensureTenantAccess();

        $product = $this->selectedProduct();

        $product->load('tenant');

        return view('products.show', compact('product'));
    }

    /* -------------------------------------------------------------------------- */

    /* Edit Product */
    /* -------------------------------------------------------------------------- */

    public function selectEdit(Request $request)
    {
        $this->ensureTenantManager();

        $product = $this->selectProduct($request);

        session()->put('products.selected_product_id', $product->id);

        return redirect()->route('products.edit');
    }

    public function edit()
    {
        $this->ensureTenantManager();

        $product = $this->selectedProduct();

        $tenants = auth()->user()->isAdministrator()
            ? $this->tenantOptions()
            : collect();

        return view('products.edit', compact('product', 'tenants'));
    }

    public function update(Request $request)
    {
        $this->ensureTenantManager();

        $product = $this->selectedProduct();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if (auth()->user()->isAdministrator()) {
            $rules['tenant_id'] = ['required', 'uuid', 'exists:tenants,id'];
        }

        $validated = $request->validate($rules);

        $oldImage = $product->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')->store('products', 'public');
        }

        try {
            $product->update([
                'tenant_id' => auth()->user()->isAdministrator()
                    ? $validated['tenant_id']
                    : $product->tenant_id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'image' => $newImage ?? $oldImage,
            ]);

            // Hapus foto lama setelah perubahan berhasil disimpan.
            if ($newImage && $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        } catch (\Throwable $e) {
            // Bersihkan foto baru jika proses penyimpanan gagal.
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $e;
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /* -------------------------------------------------------------------------- */

    /* Product Availability */
    /* -------------------------------------------------------------------------- */

    public function toggleStatus(Request $request)
    {
        $this->ensureTenantAccess();

        $request->validate([
            'product_id' => ['required', 'uuid'],
        ]);

        $product = $this->productQuery()
            ->where('id', $request->product_id)
            ->firstOrFail();

        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $product->is_active
                    ? 'Produk berhasil diaktifkan dan tersedia untuk dijual.'
                    : 'Produk berhasil dinonaktifkan dan tidak tersedia untuk dijual.'
            );
    }

    /* -------------------------------------------------------------------------- */

    /* Soft Delete */
    /* -------------------------------------------------------------------------- */

    public function destroy(Request $request)
    {
        $this->ensureTenantManager();

        $request->validate([
            'product_id' => ['required', 'uuid'],
        ]);

        $product = $this->productQuery()
            ->where('id', $request->product_id)
            ->firstOrFail();

        // Soft Delete saja, jangan hapus foto.
        $product->delete();

        if (session('products.selected_product_id') === $product->id) {
            session()->forget('products.selected_product_id');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /* -------------------------------------------------------------------------- */

    public function trash()
    {
        $this->ensureTenantManager();

        $query = Product::onlyTrashed()->with('tenant');

        // Kepala Tenant hanya melihat produk milik tenant sendiri.
        if (! auth()->user()->isAdministrator()) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

        // Pencarian produk.
        if (request()->filled('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('code', 'ILIKE', "%{$search}%")
                    ->orWhere('name', 'ILIKE', "%{$search}%");
            });
        }

        // Filter tenant untuk Administrator dan Super Admin.
        if (auth()->user()->isAdministrator() && request()->filled('tenant_id')) {
            $query->where('tenant_id', request('tenant_id'));
        }

        $products = $query
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $tenants = auth()->user()->isAdministrator()
            ? $this->tenantOptions()
            : collect();

        return view('products.trash', compact('products', 'tenants'));
    }

    public function restore(Request $request)
    {
        $this->ensureTenantManager();

        $request->validate([
            'product_id' => ['required', 'uuid'],
        ]);

        $query = Product::onlyTrashed();

        if (! auth()->user()->isAdministrator()) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

        $product = $query
            ->where('id', $request->product_id)
            ->firstOrFail();

        $product->restore();

        return redirect()
            ->route('products.trash')
            ->with('success', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete(Request $request)
    {
        $this->ensureTenantManager();

        $request->validate([
            'product_id' => ['required', 'uuid'],
        ]);

        $query = Product::onlyTrashed();

        if (! auth()->user()->isAdministrator()) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

        $product = $query
            ->where('id', $request->product_id)
            ->firstOrFail();

        $image = $product->image;

        // Hapus permanen data produk.
        $product->forceDelete();

        // Hapus foto hanya setelah data berhasil dihapus permanen.
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()
            ->route('products.trash')
            ->with('success', 'Produk dan foto berhasil dihapus permanen.');
    }
}
