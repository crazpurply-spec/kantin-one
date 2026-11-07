@extends('layouts.dashboard')
@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun')
@section('page-description', 'Perbarui informasi dan hak akses.')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>
            <span>Kembali</span>
        </a>
    @endsection

    <form action="{{ route('users.update') }}" method="POST" class="card border-0 shadow needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="card-header bg-primary-subtle border-0"></div>
        <div class="card-body">
            {{-- Information --}}
            <div class="alert alert-light border" role="alert">
                <h5 class="alert-heading fw-semibold">
                    <i class="fas fa-info-circle text-primary mt-1"></i>
                    <span>Informasi</span>
                </h5>
                <p>
                    Akun <strong>{{ $user->name }}</strong> akan diperbarui. Pastikan informasi
                    yang diubah sesuai dengan tanggung jawab pengguna.
                </p>
                <hr>
                <p class="mb-0">
                    <p class="mb-1">
                        Berikut adalah beberapa hal yang perlu diperhatikan saat edit akun:
                    </p>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item p-0 border-0">
                            Username harus unik dan tidak boleh sama dengan username pengguna lain.
                        </li>
                        <li class="list-group-item p-0 border-0">
                            Role pengguna harus dipilih sesuai dengan tanggung jawabnya.
                        </li>
                    </ol>
                </p>
            </div>
            <div class="row">
                <div class="col">
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" maxlength="255" required>
                        <div class="invalid-feedback">
                            Nama lengkap wajib diisi.
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}" pattern="[a-z0-9_-]+" maxlength="50" required>
                        <div class="invalid-feedback">
                            Username wajib diisi dan hanya boleh menggunakan huruf kecil, angka, underscore, atau tanda hubung.
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email <span class="text-muted">(Opsional)</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" maxlength="255">
                        <div class="invalid-feedback">
                            Masukkan alamat email dengan format yang valid.
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">
                            Role Pengguna <span class="text-danger">*</span>
                        </label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}"
                                    @selected(old('role', $user->role) === $role)>
                                    {{ match ($role) {
                                        'super_admin' => 'Super Admin',
                                        'administrator' => 'Administrator',
                                        'canteen_manager' => 'Kepala Pengelolaan Tenant',
                                        'tenant' => 'Kepala Tenant',
                                        'tenant_employee' => 'Pegawai Tenant',
                                        default => $role,
                                    } }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Silakan pilih role pengguna.
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Penempatan Tenant --}}
                    <div class="mb-3 d-none" id="tenantField">
                        <label for="tenant_id" class="form-label">
                            Tenant <span class="text-danger">*</span>
                        </label>
                        <select name="tenant_id" id="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
                            <option value="">Pilih Tenant</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}"
                                    @selected(old('tenant_id', $user->tenant_id) == $tenant->id)>
                                    {{ $tenant->code }} - {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Silakan pilih tenant.
                        </div>
                        @error('tenant_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Pilih tenant tempat pengguna ini bertugas.
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end align-items-center gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-danger">
                <i class="fa-regular fa-circle-xmark"></i>
                <span>Batal</span>
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Ubah Akun</span>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush
