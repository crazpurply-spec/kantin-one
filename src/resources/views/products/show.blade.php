@extends('layouts.dashboard')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')
@section('page-description', 'Informasi lengkap produk kantin.')

@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-light">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>

    @if (auth()->user()->isAdministrator() || auth()->user()->isTenant())
        <form action="{{ route('products.select-edit') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>
                Edit Produk
            </button>
        </form>
    @endif
@endsection

@section('dashboard-content')
<div class="row g-4">

    {{-- Foto Produk --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                @if ($product->image)
                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded"
                        style="width: 100%; max-height: 320px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                         style="height: 280px;">
                        <i class="fas fa-utensils fa-5x text-secondary"></i>
                    </div>
                @endif

                <div class="mt-3">
                    @if ($product->is_active)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Tersedia
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="fas fa-times-circle me-1"></i>
                            Tidak Tersedia
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Produk --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">

                <div class="mb-4">
                    <label class="text-muted small">Kode Produk</label>
                    <h6>{{ $product->code }}</h6>
                </div>

                <div class="mb-4">
                    <label class="text-muted small">Nama Produk</label>
                    <h4 class="fw-bold">{{ $product->name }}</h4>
                </div>

                <div class="mb-4">
                    <label class="text-muted small">Tenant</label>
                    <h6>
                        {{ $product->tenant->code }} -
                        {{ $product->tenant->name }}
                    </h6>
                </div>

                <div class="mb-4">
                    <label class="text-muted small">Harga</label>
                    <h4 class="fw-bold text-primary">
                        Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                    </h4>
                </div>

                <div class="mb-4">
                    <label class="text-muted small">Deskripsi</label>
                    <p class="mb-0">
                        {{ $product->description ?: 'Tidak ada deskripsi produk.' }}
                    </p>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Dibuat</label>
                        <p class="mb-0">
                            {{ $product->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Terakhir Diperbarui</label>
                        <p class="mb-0">
                            {{ $product->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection