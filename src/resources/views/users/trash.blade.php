@extends('layouts.dashboard')
@section('title', 'Akun Kadaluarsa')
@section('page-title', 'Akun Kadaluarsa')
@section('page-description', 'Kelola akun yang telah kadaluarsa.')
@section('dashboard-content')
    @section('page-actions')
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>
            <span>Kembali</span>
        </a>
    @endsection
    
    <div class="card border-0 shadow">
        <div class="card-header border-0 bg-primary-subtle"></div>
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
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-primary">
                        <tr class="small">
                            <th>#</th>
                            <th></th>
                            <th>Username</th>
                            <th>Peran</th>
                            <th>Status Sebelumnya</th>
                            <th>Tanggal Dihapus</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td class="d-flex align-items-center gap-2 p-2">
                                    <div class="bg-primary-subtle rounded-circle d-flex justify-content-center align-items-center"
                                        style="width: 2.5rem; height: 2.5rem;">
                                        <i class="fa-solid fa-circle-user fs-3 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="fw-semibold m-0">{{ $user->name }}</h6>
                                        <span class="small text-muted">{{ $user->email ?: '—' }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @if ($user->role === 'administrator')
                                        <span class="badge text-bg-danger">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-primary">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge text-bg-success">Aktif</span>
                                    @else
                                        <span class="badge text-bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $user->deleted_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- Restore --}}
                                        <form action="{{ route('users.restore') }}" method="POST" class="restore-user-form"
                                            data-name="{{ $user->name }}">
                                            @csrf

                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="Pulihkan Akun">
                                                <i class="fas fa-trash-restore"></i>
                                            </button>
                                        </form>

                                        @if (!$user->is_protected)
                                            {{-- Hapus Permanen --}}
                                            <form action="{{ route('users.force-delete') }}" method="POST" class="force-delete-user-form"
                                                data-name="{{ $user->name }}" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="Hapus Permanen">
                                                @csrf

                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permanen">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="bg-primary-subtle rounded-circle d-flex justify-content-center align-items-center
                                        mb-2 mx-auto" style="width: 4rem; height: 4rem;">
                                        <i class="fas fa-trash-alt fs-2 text-primary"></i>
                                    </div>
                                    <div class="fw-semibold mb-1">Belum ada akun yang dihapus.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $users->firstItem() ?? 0 }}
                    sampai {{ $users->lastItem() ?? 0 }}
                    dari {{ $users->total() }} akun
                </small>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush
