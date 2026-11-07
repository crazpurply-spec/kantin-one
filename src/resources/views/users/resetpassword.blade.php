@extends('layouts.dashboard')
@section('title', 'Reset Password')
@section('page-title', 'Reset Password')
@section('page-description', 'Atur ulang password akun')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>
            <span>Kembali</span>
        </a>
    @endsection

    <form action="{{ route('users.resetpassword.update') }}" method="POST"
        class="card border-0 shadow needs-validation" novalidate>
        @csrf
        @method('PATCH')

        <div class="card-header bg-primary-subtle border-0"></div>
        <div class="card-body">
            {{-- Information --}}
            <div class="alert border-0 p-0" role="alert">
                <h5 class="alert-heading fw-semibold">
                    <i class="fas fa-info-circle text-primary mt-1"></i>
                    <span>Informasi</span>
                </h5>
                <p>
                    Password Akun <strong>{{ $user->name }}</strong> akan di reset.
                    Pastikan password baru yang diatur sesuai dengan kebijakan keamanan.
                </p>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" minlength="8"
                                    autocomplete="new-password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Password wajib diisi minimal 8 karakter.
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" minlength="8" autocomplete="new-password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Konfirmasi password wajib diisi dan harus sama.
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                <i class="fas fa-key"></i>
                <span>Reset Password</span>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush
