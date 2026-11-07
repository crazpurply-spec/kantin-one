<div class="table-responsive">
    <table class="table table-sm table-hover align-middle">
        <thead class="table-primary">
            <tr class="small">
                <th>#</th>
                <th></th>

                @if (auth()->user()->isAdministrator())
                    <th>Tenant</th>
                @endif

                <th class="text-end">Harga</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        {{ $products->firstItem() + $loop->index }}
                    </td>

                    {{-- Product Information --}}
                    <td class="d-flex align-items-center gap-2 p-2">
                        <div class="bg-primary-subtle rounded d-flex justify-content-center align-items-center"
                             style="width: 2.5rem; height: 2.5rem;">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="rounded"
                                     style="width: 2.5rem; height: 2.5rem; object-fit: cover;">
                            @else
                                <i class="fas fa-utensils fs-5 text-primary"></i>
                            @endif
                        </div>

                        <div class="d-flex flex-column">
                            <h6 class="fw-semibold m-0">
                                {{ $product->name }}
                            </h6>

                            <span class="small text-muted">
                                {{ $product->code }}
                            </span>
                        </div>
                    </td>

                    {{-- Tenant --}}
                    @if (auth()->user()->isAdministrator())
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">
                                    {{ $product->tenant->name ?? '-' }}
                                </span>

                                <small class="text-muted">
                                    {{ $product->tenant->code ?? '' }}
                                </small>
                            </div>
                        </td>
                    @endif

                    {{-- Price --}}
                    <td class="text-end">
                        Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        @if ($product->is_active)
                            <span class="badge bg-success-subtle text-success">
                                Tersedia
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                Tidak Tersedia
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="d-flex justify-content-center gap-1">

                            {{-- Detail --}}
                            <form action="{{ route('products.select-detail') }}"
                                  method="POST">
                                @csrf

                                <input type="hidden"
                                       name="product_id"
                                       value="{{ $product->id }}">

                                <button type="submit"
                                        class="btn btn-sm btn-outline-info"
                                        title="Detail Produk">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>

                            {{-- Edit: Administrator & Kepala Tenant --}}
                            @if (auth()->user()->isAdministrator() || auth()->user()->isTenant())
                                <form action="{{ route('products.select-edit') }}"
                                      method="POST">
                                    @csrf

                                    <input type="hidden"
                                           name="product_id"
                                           value="{{ $product->id }}">

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Edit Produk">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Availability --}}
                            <form action="{{ route('products.toggle-status') }}"
                                method="POST"
                                class="d-inline">
                                @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <button type="submit"
                                        class="btn btn-sm {{ $product->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $product->is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk' }}">

                                    @if ($product->is_active)
                                        <i class="fas fa-toggle-on"></i>
                                    @else
                                        <i class="fas fa-toggle-off"></i>
                                    @endif
                                </button>
                            </form>

                            {{-- Delete: Administrator & Kepala Tenant --}}
                            @if (auth()->user()->isAdministrator() || auth()->user()->isTenant())
                                <form action="{{ route('products.destroy') }}"
                                    method="POST"
                                    class="d-inline delete-product-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdministrator() ? 6 : 5 }}"
                        class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>

                        <p class="text-muted mb-0">
                            Tidak ada data produk ditemukan.
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($products->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">

        <small class="text-muted">
            Menampilkan {{ $products->firstItem() ?? 0 }}
            sampai {{ $products->lastItem() ?? 0 }}
            dari {{ $products->total() }} produk
        </small>

        {{ $products->links() }}

    </div>
@endif
