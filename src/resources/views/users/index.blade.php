@extends('layouts.dashboard')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-description', 'Kelola pengguna yang memiliki akses ke sistem Kantin.ONE.')
@section('dashboard-content')
    @section('page-actions')
        {{-- Summary --}}
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="card text-bg-dark h-100" style="width: 8rem;">
                    <div class="card-body text-center">
                        <span class="small">Total Akun</span>
                        <h2 class="fw-bold m-0 mt-1">{{ $users->total() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-bg-success h-100" style="width: 8rem;">
                    <div class="card-body text-center">
                        <span class="small">Akun Aktif</span>
                        <h2 class="fw-bold m-0 mt-1">
                            {{ \App\Models\User::where('is_active', true)->count() }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-bg-danger h-100" style="width: 8rem;">
                    <div class="card-body text-center">
                        <span class="small">Akun Nonaktif</span>
                        <h2 class="fw-bold m-0 mt-1">
                            {{ \App\Models\User::where('is_active', false)->count() }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('button-list')
        <div class="card border-0 shadow mb-1">
            <div class="card-header bg-primary-subtle border-0"></div>
            <div class="card-body">
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-success">
                    <i class="fa-solid fa-user-plus me-1"></i>
                    <span>Tambah Akun</span>
                </a>
                <a href="#" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-user-lock me-1"></i>
                    <span>Pemulihan Akun</span>
                </a>
                <a href="{{ route('users.trash') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-user-lock me-1"></i>
                    <span>Akun Kadaluarsa</span>
                </a>
                <a href="#" class="btn btn-sm btn-outline-warning text-dark">
                    <i class="fas fa-key"></i>
                    <span>Reset Password</span>
                </a>
                <a href="#" class="btn btn-sm btn-outline-info text-dark">
                    <i class="fa-solid fa-eye me-1"></i>
                    <span>Histori Kelola Akun</span>
                </a>
            </div>
        </div>
    @endsection

    <div class="container-fluid p-0">
        {{-- User Table --}}
        <div class="card border-0 shadow">
            <div class="card-body">
                {{-- Filter --}}
                <form method="POST" action="{{ route('users.search') }}" class="row g-2 mb-2" id="userSearchForm">
                    @csrf
                    <div class="col-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="search" name="search" id="userSearchInput" class="form-control"
                                placeholder="Cari nama, email, atau username..." value="{{ request('search') }}"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-2">
                        <select name="role" class="form-select form-select-sm">
                            <option value="" selected disabled>Peran Akun</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(request('role') === $role)>
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="status" class="float-end form-select form-select-sm">
                            <option value="" selected disabled>Status Akun</option>
                            <option value="1" @selected(request('status') === '1')>
                                Aktif
                            </option>
                            <option value="0" @selected(request('status') === '0')>
                                Nonaktif
                            </option>
                        </select>
                    </div>
                    <div class="col-2">
                        <button type="button" class="float-end btn btn-sm btn-secondary" id="userResetFilter">
                            <i class="fas fa-undo me-1"></i>
                            <span>Reset Filter</span>
                        </button>
                    </div>
                </form>

                {{-- Table --}}
                <div id="usersTableContainer" aria-live="polite">
                    @include('users.partials.table')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush