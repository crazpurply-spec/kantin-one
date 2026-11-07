document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('tenantSearchForm');
    const searchInput = document.getElementById('tenantSearchInput');
    const statusFilter = document.getElementById('tenantStatusFilter');
    const resetButton = document.getElementById('tenantResetFilter');
    const tableContainer = document.getElementById('tenantsTableContainer');

    if (!searchForm || !tableContainer) return;

    let debounceTimer;
    let controller;

    const loadTenants = async (page = 1, updateUrl = true) => {
        if (controller) controller.abort();
        controller = new AbortController();

        const formData = new FormData(searchForm);
        formData.set('page', page);

        try {
            const response = await fetch(searchForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                body: formData,
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data tenant.');
            }

            const html = await response.text();
            tableContainer.innerHTML = html;

            if (updateUrl) {
                const params = new URLSearchParams();

                if (searchInput.value.trim()) {
                    params.set('search', searchInput.value.trim());
                }

                if (statusFilter.value !== '') {
                    params.set('status', statusFilter.value);
                }

                if (page > 1) {
                    params.set('page', page);
                }

                const query = params.toString();
                history.replaceState(
                    {},
                    '',
                    window.location.pathname + (query ? `?${query}` : '')
                );
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        }
    };

    // Search dengan debounce
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            loadTenants();
        }, 350);
    });

    // Filter status
    statusFilter.addEventListener('change', () => {
        loadTenants();
    });

    // Submit filter
    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(debounceTimer);
        loadTenants();
    });

    // Reset filter
    resetButton.addEventListener('click', () => {
        searchInput.value = '';
        statusFilter.value = '';
        loadTenants();
    });

    // Pagination AJAX
    tableContainer.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');

        if (!link) return;
        event.preventDefault();
        const url = new URL(link.href);
        const page = url.searchParams.get('page') || 1;

        loadTenants(page);
    });

    // Konfirmasi perubahan status
    tableContainer.addEventListener('submit', (event) => {
        const form = event.target.closest('.tenant-status-form');

        if (!form) return;
        event.preventDefault();
        const name = form.dataset.name;
        const action = form.dataset.status;

        Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin ${action} tenant "${name}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Konfirmasi hapus tenant
    tableContainer.addEventListener('submit', (event) => {
        const form = event.target.closest('.tenant-delete-form');

        if (!form) return;
        event.preventDefault();

        Swal.fire({
            title: 'Hapus Tenant?',
            text: `Apakah Anda yakin ingin menghapus tenant "${form.dataset.name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
