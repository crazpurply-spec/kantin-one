document.addEventListener('DOMContentLoaded', () => {
    const CONFIRMATION_KEY = 'kantin_order_confirmation';
    const CLOSING_KEY = 'kantin_tenant_sales_closing';

    const formatRupiah = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount || 0);
    };

    const getData = (key) => {
        try {
            return JSON.parse(sessionStorage.getItem(key));
        } catch {
            return null;
        }
    };

    const saveData = (key, data) => {
        sessionStorage.setItem(key, JSON.stringify(data));
    };

    const confirmation = getData(CONFIRMATION_KEY);

    const salesDate = document.getElementById('salesDate');
    const tableBody = document.getElementById('salesTableBody');
    const mobileList = document.getElementById('salesMobileList');

    let activeFilter = 'all';
    let searchQuery = '';

    const today = new Date();

    salesDate.textContent = today.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    /*
     * Simulasi transaksi:
     * Hanya pesanan berstatus completed yang dihitung
     * sebagai penjualan selesai.
     */
    const getSales = () => {
        if (!confirmation) return [];

        return (confirmation.tenant_orders || []).map((tenant) => ({
            order_number: tenant.order_number,
            pickup_code: tenant.pickup_code,
            products: tenant.products || [],
            subtotal: Number(tenant.subtotal || 0),
            status: tenant.status || 'waiting_confirmation',
            created_at: confirmation.created_at
        }));
    };

    const isCompleted = (sale) => {
        return sale.status === 'completed';
    };

    const getCompletedSales = (sales) => {
        return sales.filter(isCompleted);
    };

    const getTotalSales = (sales) => {
        return getCompletedSales(sales).reduce(
            (total, sale) => total + sale.subtotal,
            0
        );
    };

    const updateSummary = (sales) => {
        const completed = getCompletedSales(sales);

        const pending = sales.filter(
            (sale) => !isCompleted(sale)
        );

        const total = getTotalSales(sales);

        const average = completed.length
            ? total / completed.length
            : 0;

        document.getElementById('totalSales').textContent =
            formatRupiah(total);

        document.getElementById('completedOrders').textContent =
            completed.length;

        document.getElementById('pendingOrders').textContent =
            pending.length;

        document.getElementById('averageSales').textContent =
            formatRupiah(average);

        document.getElementById('verificationCount').textContent =
            `${completed.length} Transaksi`;

        document.getElementById('verificationTotal').textContent =
            formatRupiah(total);

        const warning = document.getElementById('verificationWarning');

        if (pending.length > 0) {
            warning.innerHTML = `
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>
                    Masih terdapat ${pending.length} pesanan yang belum selesai.
                    Selesaikan seluruh pesanan sebelum melakukan closing.
                </span>
            `;
        } else {
            warning.innerHTML = `
                <i class="fa-solid fa-circle-check"></i>
                <span>
                    Seluruh pesanan telah selesai. Silakan periksa
                    kembali total penjualan sebelum melakukan closing.
                </span>
            `;
        }
    };

    const createStatusBadge = (sale) => {
        const badge = document.createElement('span');

        badge.className = isCompleted(sale)
            ? 'sales-status completed'
            : 'sales-status pending';

        badge.textContent = isCompleted(sale)
            ? 'Selesai'
            : 'Belum Selesai';

        return badge;
    };

    const showSaleDetail = (sale) => {
        const products = sale.products.map((product) => {
            return `${product.name} × ${product.quantity}`;
        }).join('\n');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Detail Transaksi',
                html: `
                    <div style="text-align:left;font-size:13px">
                        <p><strong>Nomor Pesanan:</strong> ${sale.order_number}</p>
                        <p><strong>Kode Pengambilan:</strong> ${sale.pickup_code}</p>
                        <hr>
                        <strong>Produk:</strong>
                        <pre style="white-space:pre-wrap;font-family:inherit;margin-top:8px">${products}</pre>
                        <hr>
                        <strong>Total: ${formatRupiah(sale.subtotal)}</strong>
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#198754'
            });
        } else {
            alert(
                `Nomor Pesanan: ${sale.order_number}\n` +
                `Kode Pengambilan: ${sale.pickup_code}\n\n` +
                `${products}\n\n` +
                `Total: ${formatRupiah(sale.subtotal)}`
            );
        }
    };

    const renderTable = (sales) => {
        tableBody.innerHTML = '';
        mobileList.innerHTML = '';

        const filtered = sales.filter((sale) => {
            const matchesFilter =
                activeFilter === 'all' ||
                (activeFilter === 'completed' && isCompleted(sale)) ||
                (activeFilter === 'pending' && !isCompleted(sale));

            const matchesSearch =
                sale.order_number.toLowerCase().includes(searchQuery);

            return matchesFilter && matchesSearch;
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="sales-empty">
                        Tidak ada transaksi yang ditemukan.
                    </td>
                </tr>
            `;

            mobileList.innerHTML = `
                <div class="sales-empty">
                    <i class="fa-solid fa-receipt"></i>
                    Tidak ada transaksi yang ditemukan.
                </div>
            `;

            return;
        }

        filtered.forEach((sale) => {
            const row = document.createElement('tr');

            const values = [
                sale.order_number,
                new Date(sale.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }),
                `${sale.products.reduce(
                    (sum, product) => sum + Number(product.quantity || 0),
                    0
                )} Produk`,
                formatRupiah(sale.subtotal)
            ];

            values.forEach((value) => {
                const cell = document.createElement('td');
                cell.textContent = value;
                row.appendChild(cell);
            });

            const statusCell = document.createElement('td');
            statusCell.appendChild(createStatusBadge(sale));

            const actionCell = document.createElement('td');
            const detailButton = document.createElement('button');

            detailButton.className = 'btn-detail-sale';
            detailButton.textContent = 'Detail';

            detailButton.addEventListener('click', () => {
                showSaleDetail(sale);
            });

            actionCell.appendChild(detailButton);

            row.append(statusCell, actionCell);
            tableBody.appendChild(row);

            // Tampilan mobile.
            const mobileCard = document.createElement('div');
            mobileCard.className = 'sales-mobile-card';

            const top = document.createElement('div');
            top.className = 'sales-mobile-card-top';

            const info = document.createElement('div');

            const number = document.createElement('strong');
            number.textContent = sale.order_number;

            const time = document.createElement('small');
            time.textContent = values[1];

            info.append(number, time);

            top.append(info, createStatusBadge(sale));

            const bottom = document.createElement('div');
            bottom.className = 'sales-mobile-card-bottom';

            const total = document.createElement('strong');
            total.textContent = formatRupiah(sale.subtotal);

            const detail = document.createElement('button');
            detail.className = 'btn-detail-sale';
            detail.textContent = 'Detail';

            detail.addEventListener('click', () => {
                showSaleDetail(sale);
            });

            bottom.append(total, detail);
            mobileCard.append(top, bottom);

            mobileList.appendChild(mobileCard);
        });
    };

    const updateClosingButton = () => {
        const checkbox = document.getElementById('confirmVerification');
        const button = document.getElementById('closeSales');

        const sales = getSales();
        const hasPending = sales.some((sale) => !isCompleted(sale));

        const closing = getData(CLOSING_KEY);

        const alreadyClosed =
            closing && closing.closed === true;

        button.disabled =
            !checkbox.checked ||
            hasPending ||
            alreadyClosed;

        if (alreadyClosed) {
            button.innerHTML = `
                <i class="fa-solid fa-circle-check"></i>
                Penjualan Telah Ditutup
            `;
        }
    };

    const render = () => {
        const sales = getSales();

        updateSummary(sales);
        renderTable(sales);
        updateClosingButton();
    };

    document.querySelectorAll('#salesFilters button').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('#salesFilters button')
                .forEach((item) => item.classList.remove('active'));

            button.classList.add('active');
            activeFilter = button.dataset.filter;

            render();
        });
    });

    document.getElementById('searchSales').addEventListener('input', (event) => {
        searchQuery = event.target.value.trim().toLowerCase();
        render();
    });

    document.getElementById('confirmVerification')
        .addEventListener('change', updateClosingButton);

    document.getElementById('closeSales').addEventListener('click', () => {
        const sales = getSales();

        const pending = sales.filter(
            (sale) => !isCompleted(sale)
        );

        if (pending.length > 0) {
            return;
        }

        const checkbox = document.getElementById('confirmVerification');

        if (!checkbox.checked) return;

        const closingData = {
            closed: true,
            closed_at: new Date().toISOString(),
            total_transactions: getCompletedSales(sales).length,
            total_sales: getTotalSales(sales)
        };

        const confirmClosing = () => {
            saveData(CLOSING_KEY, closingData);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Penjualan Berhasil Ditutup',
                    text: 'Verifikasi penjualan tenant telah selesai.',
                    confirmButtonColor: '#198754'
                });
            } else {
                alert('Penjualan berhasil ditutup.');
            }

            render();
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Closing?',
                text: 'Pastikan total penjualan sudah sesuai sebelum melanjutkan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tutup Penjualan',
                cancelButtonText: 'Periksa Kembali',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmClosing();
                }
            });
        } else if (confirm('Apakah kamu yakin ingin menutup penjualan?')) {
            confirmClosing();
        }
    });

    render();
});