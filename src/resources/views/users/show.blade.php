@extends('layouts.dashboard')
@section('title', 'Detail Akun')
@section('page-title', 'Detail Akun')
@section('page-description', 'Informasi lengkap akun pengguna')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>
            <span>Kembali</span>
        </a>
    @endsection
    
    <div class="row g-2">
        {{-- Informasi Pengguna --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header {{ $user->is_active ? 'bg-success' : 'bg-danger' }} border-0">
                </div>
                <div class="card-body">
                    <div class="d-flex flex-row flex-wrap justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Akun</h5>
                        @if ($user->is_active)
                            <h5 class="m-0">
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-globe"></i>
                                    <span>Aktif</span>
                                </span>
                            </h5>
                        @else
                            <h5 class="m-0">
                                <span class="badge bg-danger">
                                    <i class="fa-solid fa-plane-slash"></i>
                                    <span>Nonaktif</span>
                                </span>
                            </h5>
                        @endif
                    </div>
                    <div class="d-flex flex-row flex-wrap justify-content-around align-items-center
                        border-bottom border-top py-1 my-3">
                        <div class="d-flex align-items-center gap-3 my-2">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center
                                justify-content-center" style="width: 65px; height: 65px; font-size: 24px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <span class="text-muted">{{ '@' . $user->username }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <small class="text-muted d-block mb-1">Verifikasi Email</small>
                            <div class="fw-semibold">
                                @if ($user->email_verified_at)
                                    <span class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Terverifikasi
                                    </span>
                                @elseif ($user->email)
                                    <span class="text-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Belum Terverifikasi
                                    </span>
                                @else
                                    <span class="text-muted">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Nama Akun</small>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Username</small>
                                <div class="fw-semibold">{{ $user->username }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Email</small>
                                <div class="fw-semibold">{{ $user->email ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Peran</small>
                                <h5 class="fw-semibold m-0">
                                    @if ($user->role === 'super_admin')
                                        <span class="badge text-bg-danger">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @elseif ($user->role === 'administrator')
                                        <span class="badge text-bg-warning">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-primary">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @endif
                                </h5>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Tanggal Dibuat</small>
                                <div class="fw-semibold">
                                    {{ $user->created_at?->format('d F Y, H:i') ?? '—' }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Terakhir Diperbarui</small>
                                <div class="fw-semibold">
                                    {{ $user->updated_at?->format('d F Y, H:i') ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi Pengguna --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow mb-2">
                <div class="card-header bg-primary-subtle border-0"></div>
                <div class="card-body">
                    @if (!$user->is_protected)
                        {{-- Edit Pengguna --}}
                        <form action="{{ route('users.select-edit') }}" method="POST" class="mb-2">
                            @csrf

                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-edit"></i>
                                <span>Edit Akun</span>
                            </button>
                        </form>

                        @if ($user->id !== auth()->id())
                            {{-- Toggle Status --}}
                            <form action="{{ route('users.toggle-status') }}" method="POST" class="status-form mb-2"
                                    data-name="{{ $user->name }}" data-active="{{ $user->is_active ? '1' : '0' }}">
                                @csrf

                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} w-100">
                                    <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                    <span>
                                        {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                                    </span>
                                </button>
                            </form>

                            @if (!$user->is_active)
                                {{-- Soft Delete --}}
                                <form action="{{ route('users.destroy') }}" method="POST" class="delete-user-form" data-name="{{ $user->name }}">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus Akun</span>
                                    </button>
                                </form>

                            @endif
                        @endif

                    @else
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle"></i>
                            Ini adalah akun yang sedang Anda gunakan.
                        </div>
                    @endif
                </div>
            </div>
            <div class="card border-0 shadow">
                <div class="card-header bg-warning-subtle border-0"></div>
                <div class="card-body">
                    <h6 class="fw-semibold mb-2">
                        <i class="fas fa-info-circle text-primary"></i>
                        <span>Informasi</span>
                    </h6>
                    <p class="small m-0">
                        Akun yang dihapus akan dipindahkan ke
                        <strong class="text-danger">Akun Kadalauarsa (Soft Delete)</strong>,
                        sehingga akun masih tersimpan di database dan bisa dipulihkan.
                        <strong>Hubungi administrator untuk mengaktifkan kembali akun yang telah dihapus.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush