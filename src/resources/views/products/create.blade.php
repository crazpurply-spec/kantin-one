@extends('layouts.dashboard')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-description', 'Tambahkan produk baru ke dalam katalog kantin.')

@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
@endsection

@section('dashboard-content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('products.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (auth()->user()->isAdministrator())
                <div class="mb-3">
                    <label for="tenant_id" class="form-label">
                        Tenant <span class="text-danger">*</span>
                    </label>

                    <select name="tenant_id" id="tenant_id"
                            class="form-select @error('tenant_id') is-invalid @enderror"
                            required>
                        <option value="">Pilih Tenant</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}"
                                {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->code }} - {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('tenant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">
                    Nama Produk <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       maxlength="255" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    Deskripsi Produk
                </label>
                <textarea name="description" id="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">
                    Harga Produk <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="price" id="price"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}"
                           min="0.01" step="0.01" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="image" class="form-label">Foto Produk</label>
                <input type="file" name="image" id="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                </div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}"
                   class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection