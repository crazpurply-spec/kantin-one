@extends('layouts.dashboard')
@section('page-title', 'Tambah Tenant')
@section('page-description', 'Tambahkan data tenant baru ke dalam Kantin.ONE.')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    @endsection

    <div class="row">
        <div class="col-xl-9 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-1">Form Tambah Tenant</h5>
                    <small class="text-muted">
                        Lengkapi informasi tenant berikut.
                    </small>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenants.store') }}" method="POST" id="tenantCreateForm">
                        @csrf

                        <div class="row g-3">
                            {{-- Kode Tenant --}}
                            <div class="col-md-6">
                                <label class="form-label">Kode Tenant</label>
                                <input type="text" class="form-control" value="Dibuat otomatis oleh sistem" disabled>
                                <small class="text-muted">
                                    Kode unik akan dibuat secara otomatis ketika tenant disimpan.
                                </small>
                            </div>

                            {{-- Nama Tenant --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Nama Tenant <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Masukkan nama tenant" maxlength="255" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="Contoh: 081234567890" maxlength="30">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="Contoh: tenant@email.com" maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Lokasi --}}
                            <div class="col-12">
                                <label for="location" class="form-label">Lokasi Tenant</label>
                                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="Contoh: Gedung A, Lantai 1" maxlength="255">
                                @error('location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Masukkan deskripsi singkat tenant">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tenants.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Tenant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
