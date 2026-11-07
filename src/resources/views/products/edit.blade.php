@extends('layouts.dashboard')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-description', 'Perbarui informasi produk kantin.')

@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
@endsection

@section('dashboard-content')
<div class="card border-0 shadow-sm">
    <div class="card-body">

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

        <form action="{{ route('products.update') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kode Produk</label>
                <input type="text" class="form-control"
                       value="{{ $product->code }}" disabled>
            </div>

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
                                {{ old('tenant_id', $product->tenant_id) == $tenant->id ? 'selected' : '' }}>
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
                       value="{{ old('name', $product->name) }}"
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
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
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
                           value="{{ old('price', $product->price) }}"
                           min="0.01" step="0.01" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Produk Saat Ini</label>

                @if ($product->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="img-thumbnail"
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    </div>
                @else
                    <p class="text-muted">Belum ada foto produk.</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="image" class="form-label">
                    Ganti Foto Produk
                </label>
                <input type="file" name="image" id="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">
                    Kosongkan jika tidak ingin mengganti foto.
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection