<div class="table-responsive">
    <table class="table table-sm table-hover align-middle">
        <thead class="table-primary">
            <tr class="small">
                <th>#</th>
                <th></th>
                <th>No. Telp</th>
                <th>Lokasi</th>
                <th class="text-center">Jumlah Pegawai</th>
                <th class="text-center">Status</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tenants as $tenant)
                <tr>
                    <td>{{ $tenants->firstItem() + $loop->index }}</td>
                    <td class="d-flex align-items-center gap-2 p-2">
                        <div class="bg-primary-subtle rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 2.5rem; height: 2.5rem;">
                            <i class="fa-solid fa-circle-user fs-3 text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="fw-semibold m-0">{{ $tenant->name }}</h6>
                            <span class="small text-muted">{{ $tenant->code }}</span>
                            {{ $tenant->head?->name ?? 'Belum ditentukan' }}
                        </div>
                    </td>
                    <td>
                        @if ($tenant->phone)
                            <small class="text-muted">
                                {{ $tenant->phone }}
                            </small>
                        @endif
                    </td>
                    <td>{{ $tenant->location ?: '-' }}</td>
                    <td class="text-center">{{ $tenant->users_count }}</td>
                    <td class="text-center">
                        @if ($tenant->is_active)
                            <span class="badge bg-success-subtle text-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Detail --}}
                            <form action="{{ route('tenants.select-detail') }}"method="POST">
                                @csrf

                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-info" title="Detail Tenant">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </form>

                            {{-- Edit --}}
                            <form action="{{ route('tenants.select-edit') }}" method="POST">
                                @csrf

                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Edit Tenant">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>

                            {{-- Status --}}
                            <form action="{{ route('tenants.toggle-status') }}" method="POST" class="tenant-status-form"
                                  data-name="{{ $tenant->name }}" data-status="{{ $tenant->is_active ? 'nonaktifkan' : 'aktifkan' }}">
                                @csrf

                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                    title="{{ $tenant->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Tenant">
                                    <i class="fas {{ $tenant->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form action="{{ route('tenants.destroy') }}" method="POST" class="tenant-delete-form"
                                data-name="{{ $tenant->name }}">
                                @csrf

                                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Tenant">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">
                            Tidak ada data tenant ditemukan.
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($tenants->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
            Menampilkan {{ $tenants->firstItem() ?? 0 }}
            sampai {{ $tenants->lastItem() ?? 0 }}
            dari {{ $tenants->total() }} tenant
        </small>

        {{ $tenants->links() }}
    </div>
@endif
