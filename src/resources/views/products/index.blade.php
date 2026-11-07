
@extends('layouts.dashboard')

@section('page-title', 'Manajemen Produk')
@section('page-description', 'Kelola produk yang tersedia pada seluruh tenant Kantin.ONE.')

@section('page-actions')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Produk
    </a>
    <a href="{{ route('products.trash') }}"
        class="nav-link {{ request()->routeIs('products.trashed') ? 'active' : '' }}">
            <i class="fas fa-trash-alt me-2"></i>
            Produk Terhapus
    </a>
@endsection

@section('dashboard-content')

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">Daftar Produk</h5>
                <small class="text-muted">
                    Informasi produk yang terdaftar pada Kantin.ONE.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- Filter --}}
        <form id="productSearchForm"
              action="{{ route('products.search') }}"
              method="POST">
            @csrf

            <div class="row g-2 mb-3">

                {{-- Search --}}
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               id="productSearchInput"
                               class="form-control"
                               placeholder="Cari kode atau nama produk..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Tenant Filter --}}
                @if (auth()->user()->isAdministrator())
                    <div class="col-md-3">
                        <select name="tenant_id"
                                id="productTenantFilter"
                                class="form-select">
                            <option value="">Semua Tenant</option>

                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}"
                                    {{ request('tenant_id') === $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Status Filter --}}
                <div class="col-md-2">
                    <select name="status"
                            id="productStatusFilter"
                            class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>

                    <button type="button"
                            id="productResetFilter"
                            class="btn btn-outline-secondary"
                            title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>

            </div>
        </form>

        {{-- Table --}}
        <div id="productsTableContainer">
            @include('products.partials.table')
        </div>

    </div>
</div>

@endsection
