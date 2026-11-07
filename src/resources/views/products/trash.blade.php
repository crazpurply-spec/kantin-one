@extends('layouts.dashboard')

@section('title', 'Produk Terhapus')
@section('page-title', 'Produk Terhapus')
@section('page-description', 'Pulihkan produk atau hapus produk secara permanen.')

@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali ke Produk
    </a>
@endsection

@section('dashboard-content')

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('products.trash') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari kode atau nama produk..."
                           value="{{ request('search') }}">
                </div>

                @if (auth()->user()->isAdministrator())
                    <div class="col-md-4">
                        <select name="tenant_id" class="form-select">
                            <option value="">Semua Tenant</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}"
                                    {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->code }} - {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Cari
                    </button>

                    <a href="{{ route('products.trash') }}"
                       class="btn btn-light">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Produk</th>

                        @if (auth()->user()->isAdministrator())
                            <th>Tenant</th>
                        @endif

                        <th>Harga</th>
                        <th>Dihapus Pada</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($product->image)
                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width:45px;height:45px;">
                                            <i class="fas fa-utensils text-secondary"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $product->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $product->code }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            @if (auth()->user()->isAdministrator())
                                <td>
                                    {{ $product->tenant->name ?? '-' }}
                                </td>
                            @endif

                            <td>
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $product->deleted_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <div class="d-flex gap-1">

                                    <form action="{{ route('products.restore') }}"
                                          method="POST"
                                          class="restore-product-form">
                                        @csrf
                                        <input type="hidden"
                                               name="product_id"
                                               value="{{ $product->id }}">

                                        <button type="submit"
                                                class="btn btn-sm btn-success"
                                                title="Pulihkan Produk">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('products.force-delete') }}"
                                          method="POST"
                                          class="force-delete-product-form">
                                        @csrf
                                        <input type="hidden"
                                               name="product_id"
                                               value="{{ $product->id }}">

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdministrator() ? 6 : 5 }}"
                                class="text-center py-4 text-muted">
                                <i class="fas fa-trash-alt fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada produk terhapus.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>

    </div>
</div>

@endsection