document.addEventListener('submit', function (event) {
    const restoreForm = event.target.closest('.restore-product-form');
    const deleteForm = event.target.closest('.force-delete-product-form');

    if (!restoreForm && !deleteForm) return;

    event.preventDefault();

    if (restoreForm) {
        Swal.fire({
            title: 'Pulihkan Produk?',
            text: 'Produk akan dikembalikan ke daftar produk aktif.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                restoreForm.submit();
            }
        });
    }

    if (deleteForm) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: 'Data produk dan foto akan dihapus secara permanen dan tidak dapat dipulihkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                deleteForm.submit();
            }
        });
    }
});

document.addEventListener('submit', function (event) {
    const form = event.target.closest('.delete-product-form');

    if (!form) return;

    event.preventDefault();

    Swal.fire({
        title: 'Hapus Produk?',
        text: 'Produk akan dihapus dari daftar. Tindakan ini tidak dapat dibatalkan melalui halaman biasa.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {

    const searchForm = document.getElementById('productSearchForm');
    const searchInput = document.getElementById('productSearchInput');
    const tenantFilter = document.getElementById('productTenantFilter');
    const statusFilter = document.getElementById('productStatusFilter');
    const resetButton = document.getElementById('productResetFilter');
    const tableContainer = document.getElementById('productsTableContainer');

    if (!searchForm || !tableContainer) return;

    let debounceTimer;
    let controller;

    const loadProducts = async (page = 1, updateUrl = true) => {

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
                throw new Error('Gagal memuat data produk.');
            }

            const html = await response.text();
            tableContainer.innerHTML = html;

            if (updateUrl) {
                const params = new URLSearchParams();

                if (searchInput.value.trim()) {
                    params.set('search', searchInput.value.trim());
                }

                if (tenantFilter && tenantFilter.value !== '') {
                    params.set('tenant_id', tenantFilter.value);
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
            loadProducts();
        }, 350);
    });

    // Filter tenant
    if (tenantFilter) {
        tenantFilter.addEventListener('change', () => {
            loadProducts();
        });
    }

    // Filter status
    statusFilter.addEventListener('change', () => {
        loadProducts();
    });

    // Submit filter
    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(debounceTimer);
        loadProducts();
    });

    // Reset filter
    resetButton.addEventListener('click', () => {
        searchInput.value = '';

        if (tenantFilter) {
            tenantFilter.value = '';
        }

        statusFilter.value = '';

        loadProducts();
    });

    // Pagination AJAX
    tableContainer.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');

        if (!link) return;

        event.preventDefault();

        const url = new URL(link.href);
        const page = url.searchParams.get('page') || 1;

        loadProducts(page);
    });

});
