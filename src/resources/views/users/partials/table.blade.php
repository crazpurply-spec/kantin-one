<div class="table-responsive">
    <table class="table table-sm table-hover align-middle">
        <thead class="table-primary">
            <tr class="small">
                <th>#</th>
                <th></th>
                <th>Username</th>
                <th>Peran</th>
                <th>Status</th>
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
                    </td>
                    <td>
                        @if ($user->is_active)
                            <span class="badge text-bg-success">Aktif</span>
                        @else
                            <span class="badge text-bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            {{-- Detail --}}
                            <form action="{{ route('users.select-detail') }}" method="POST">
                                @csrf

                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="Detail Akun">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>

                            @if (!$user->is_protected)
                                {{-- Edit --}}
                                <form action="{{ route('users.select-edit') }}" method="POST">
                                    @csrf
                                    
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="Edit Akun">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>

                                {{-- Reset Password --}}
                                <form action="{{ route('users.resetpassword.start') }}" method="POST">
                                    @csrf
                                    
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>

                                @if ($user->id !== auth()->id())
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('users.toggle-status') }}" method="POST" class="status-form"
                                        data-name="{{ $user->name }}" data-active="{{ $user->is_active ? '1' : '0' }}">
                                        @csrf
                                        
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (!$user->is_active && !$user->is_protected)
                                {{-- Soft Delete --}}
                                <form action="{{ route('users.destroy') }}" method="POST" class="delete-user-form"
                                    data-name="{{ $user->name }}">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="Hapus Akun">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="bg-primary-subtle rounded-circle d-flex justify-content-center align-items-center
                            mb-2 mx-auto" style="width: 4rem; height: 4rem;">
                            <i class="fas fa-users fs-2 text-primary"></i>
                        </div>
                        <div class="fw-semibold mb-1">Tidak ada akun ditemukan</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <small class="text-muted">
        Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }}
        dari {{ $users->total() }} akun
    </small>
    {{ $users->links() }}
</div>