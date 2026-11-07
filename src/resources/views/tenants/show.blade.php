@extends('layouts.dashboard')
@section('page-title', 'Detail Tenant')
@section('page-description', 'Informasi lengkap tenant Kantin.ONE.')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <form action="{{ route('tenants.select-edit') }}" method="POST">
            @csrf

            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit Tenant
            </button>
        </form>
    @endsection

    <div class="row g-4">
        {{-- Informasi Utama --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informasi Tenant</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-1">{{ $tenant->name }}</h4>
                            <span class="text-muted">
                                {{ $tenant->code }}
                            </span>
                        </div>
                        @if ($tenant->is_active)
                            <span class="badge bg-success-subtle text-success fs-6">
                                <i class="fas fa-check-circle me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary fs-6">
                                <i class="fas fa-ban me-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                    <hr>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-phone me-1"></i> Nomor Telepon
                            </small>
                            <span class="fw-medium">{{ $tenant->phone ?: '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-envelope me-1"></i> Email
                            </small>
                            <span class="fw-medium">{{ $tenant->email ?: '-' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i> Lokasi
                            </small>
                            <span class="fw-medium">{{ $tenant->location ?: '-' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-align-left me-1"></i> Deskripsi
                            </small>
                            <div class="text-body">
                                @if ($tenant->description)
                                    {!! nl2br(e($tenant->description)) !!}
                                @else
                                    <span class="text-muted">Belum ada deskripsi.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik dan Kepala Tenant --}}
        <div class="col-xl-4 col-lg-5">
            {{-- Jumlah Pengguna --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Jumlah Pengguna</small>
                            <h3 class="mb-0">{{ $tenant->users_count }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kepala Tenant --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Kepala Tenant</h5>
                </div>
                <div class="card-body">
                    @if ($tenant->head)
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center
                                justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="fw-semibold">{{ $tenant->head->name }}</div>
                                <small class="text-muted">{{ '@' . $tenant->head->username }}</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Email</small>
                            <span>{{ $tenant->head->email ?: '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Status Akun</small>
                            @if ($tenant->head->is_active)
                                <span class="badge bg-success-subtle text-success">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <form action="{{ route('users.select-detail') }}" method="POST">
                            @csrf

                            <input type="hidden" name="user_id"value="{{ $tenant->head->id }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i> Detail Pengguna
                            </button>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada Kepala Tenant yang ditentukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Daftar Pegawai Tenant --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Daftar Pegawai Tenant</h5>
                    <small class="text-muted">
                        Daftar pegawai yang terdaftar pada tenant ini.
                    </small>
                </div>
                <span class="badge bg-primary-subtle text-primary">
                    {{ $tenant->employees->count() }} Pegawai
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="6%">No.</th>
                            <th>Nama Pegawai</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenant->employees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center
                                            justify-content-center me-2" style="width: 36px; height: 36px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $employee->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $employee->username ?: '-' }}</td>
                                <td>{{ $employee->email ?: '-' }}</td>
                                <td>
                                    @if ($employee->is_active)
                                        <span class="badge bg-success-subtle text-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('users.select-detail') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="user_id" value="{{ $employee->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Belum ada pegawai yang terdaftar pada tenant ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
