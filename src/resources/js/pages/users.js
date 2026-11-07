document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // BOOTSTRAP FORM VALIDATION
    // ==========================================
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', function (event) {

            // Username lowercase
            const username = form.querySelector('#username');

            if (username) {
                username.value = username.value.toLowerCase();
            }

            // Password confirmation
            const password = form.querySelector('#password');
            const confirmation = form.querySelector('#password_confirmation');

            if (password && confirmation) {
                confirmation.setCustomValidity(
                    confirmation.value !== password.value
                        ? 'Konfirmasi password tidak sama.'
                        : ''
                );
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });

    // ==========================================
    // USERNAME LOWERCASE
    // ==========================================
    document.addEventListener('input', function (event) {
        if (event.target.id !== 'username') return;

        const start = event.target.selectionStart;
        const end = event.target.selectionEnd;
        event.target.value = event.target.value.toLowerCase();
        event.target.setSelectionRange(start, end);

    });

    // ==========================================
    // TENANT FIELD: SHOW / HIDE BASED ON ROLE
    // ==========================================
    const roleSelect = document.getElementById('role');
    const tenantField = document.getElementById('tenantField');
    const tenantSelect = document.getElementById('tenant_id');

    if (roleSelect && tenantField && tenantSelect) {

        function toggleTenantField() {
            const selectedRole = roleSelect.value;

            const requiresTenant = [
                'tenant',
                'tenant_employee'
            ].includes(selectedRole);

            tenantField.classList.toggle('d-none', !requiresTenant);

            tenantSelect.required = requiresTenant;

            if (!requiresTenant) {
                tenantSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', toggleTenantField);

        // Jalankan saat halaman pertama kali dibuka
        toggleTenantField();
    }

    // ==========================================
    // LIVE SEARCH, FILTER & PAGINATION
    // POST REQUEST - NO SENSITIVE DATA IN URL
    // ==========================================
    const searchForm = document.getElementById('userSearchForm');
    const searchInput = document.getElementById('userSearchInput');
    const tableContainer = document.getElementById('usersTableContainer');
    const resetButton = document.getElementById('userResetFilter');

    if (searchForm && searchInput && tableContainer) {
        let searchTimeout;
        let controller;

        async function loadUsers(page = 1) {
            if (controller) {
                controller.abort();
            }

            const currentController = new AbortController();
            controller = currentController;

            const formData = new FormData(searchForm);

            // Pagination dikirim melalui POST body
            formData.set('page', page);

            tableContainer.style.opacity = '0.5';

            try {
                const response = await fetch(searchForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    body: formData,
                    signal: currentController.signal
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data pengguna.');
                }

                const html = await response.text();

                // Abaikan response dari request yang sudah tidak aktif
                if (controller !== currentController) return;

                tableContainer.innerHTML = html;

            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error(error);

                    Swal.fire(
                        'Gagal',
                        'Tidak dapat memuat data pengguna.',
                        'error'
                    );
                }
            } finally {
                if (controller === currentController) {
                    tableContainer.style.opacity = '1';
                }
            }
        }

        // Live Search
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                loadUsers(1);
            }, 350);
        });

        // Filter Role dan Status
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();

            clearTimeout(searchTimeout);
            loadUsers(1);
        });

        // Reset Filter
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                searchForm.reset();

                clearTimeout(searchTimeout);
                loadUsers(1);
            });
        }

        // Filter otomatis ketika pilihan berubah
        searchForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function () {
                clearTimeout(searchTimeout);
                loadUsers(1);
            });
        });

        // Pagination AJAX
        tableContainer.addEventListener('click', function (event) {
            const link = event.target.closest('.pagination a');

            if (!link) return;

            event.preventDefault();

            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;

            loadUsers(page);
        });
    }

    // ==========================================
    // SWEETALERT: ACTIVATE / DEACTIVATE
    // ==========================================
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('.status-form');
        if (!form) return;

        event.preventDefault();
        const name = form.dataset.name;
        const isActive = form.dataset.active === '1';

        Swal.fire({
            title: isActive ? 'Nonaktifkan pengguna?' : 'Aktifkan pengguna?',
            text: `Apakah Anda yakin ingin ${ isActive ? 'menonaktifkan' : 'mengaktifkan' } akun ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // ==========================================
    // SWEETALERT: DELETE USER (SOFT DELETE)
    // ==========================================
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('.delete-user-form');
        if (!form) return;

        event.preventDefault();
        const name = form.dataset.name;

        Swal.fire({
            title: 'Hapus Akun Pengguna?',
            html: `
                Akun <strong>${escapeHtml(name)}</strong>
                akan dipindahkan ke data terhapus.
                <br><br>
                <small class="text-muted">
                    Data masih tersimpan dan dapat dipulihkan.
                </small>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // ==========================================
    // PASSWORD VISIBILITY
    // ==========================================
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.toggle-password');
        if (!button) return;

        const target = document.getElementById(button.dataset.target);
        if (!target) return;

        const isPassword = target.type === 'password';
        target.type = isPassword ? 'text' : 'password';
        button.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        button.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
    });

    // ==========================================
    // HTML ESCAPE FOR SWEETALERT
    // ==========================================
    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => {
            const entities = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return entities[character];
        });
    }

    // Restore user confirmation
    document.addEventListener('submit', function (event) {
        const form = event.target.closest('.restore-user-form');
        if (!form) return;

        event.preventDefault();

        Swal.fire({
            title: 'Pulihkan Pengguna?',
            text: `Apakah Anda yakin ingin memulihkan akun ${form.dataset.name}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Permanent delete confirmation - Double Confirmation
    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.force-delete-user-form');
        if (!form) return;

        event.preventDefault();

        const name = escapeHtml(form.dataset.name);

        // Confirmation pertama
        const firstConfirm = await Swal.fire({
            title: 'Hapus Permanen?',
            html: `
                Akun <strong>${name}</strong> akan dihapus permanen.
                <br><br>
                <span class="text-danger">
                    Data tidak dapat dipulihkan kembali!
                </span>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!firstConfirm.isConfirmed) return;

        // Confirmation kedua
        const secondConfirm = await Swal.fire({
            title: 'Konfirmasi Terakhir!',
            html: `
                Apakah Anda benar-benar yakin ingin menghapus
                akun <strong>${name}</strong> secara permanen?
                <br><br>
                <strong class="text-danger">
                    Tindakan ini tidak dapat dibatalkan!
                </strong>
            `,
            icon: 'error',
            input: 'text',
            inputPlaceholder: 'Ketik HAPUS untuk melanjutkan',
            inputValidator: (value) => {
                if (value !== 'HAPUS') {
                    return 'Ketik HAPUS dengan huruf kapital untuk melanjutkan.';
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Hapus Permanen',
            cancelButtonText: 'Batalkan',
            reverseButtons: true
        });

        if (secondConfirm.isConfirmed) {
            form.submit();
        }
    });
});
