
@extends('layouts.dashboard')

@section('page-title', 'Manajemen Tenant')
@section('page-description', 'Kelola data tenant yang terdaftar pada Kantin.ONE.')

@section('page-actions')
    <a href="{{ route('tenants.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Tenant
    </a>
@endsection

@section('dashboard-content')

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">Daftar Tenant</h5>
                <small class="text-muted">
                    Informasi seluruh tenant yang terdaftar.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">

        {{-- Filter --}}
        <form id="tenantSearchForm"
              action="{{ route('tenants.search') }}"
              method="POST">
            @csrf

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               id="tenantSearchInput"
                               class="form-control"
                               placeholder="Cari kode, nama, atau lokasi tenant..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="status"
                            id="tenantStatusFilter"
                            class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>

                    <button type="button"
                            id="tenantResetFilter"
                            class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div id="tenantsTableContainer">
            @include('tenants.partials.table')
        </div>

    </div>
</div>

@endsection