document.addEventListener('DOMContentLoaded', () => {
    const CONFIRMATION_KEY = 'kantin_order_confirmation';
    const PENDING_KEY = 'kantin_pending_order';

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

    const confirmation = getData(CONFIRMATION_KEY);
    const pendingOrder = getData(PENDING_KEY);

    const trackingList = document.getElementById('tenantTrackingList');

    if (!confirmation || !pendingOrder) {
        trackingList.innerHTML = `
            <div class="status-empty">
                <i class="fa-solid fa-receipt"></i>
                <h3>Pesanan Tidak Ditemukan</h3>
                <p>Silakan lakukan pemesanan terlebih dahulu.</p>
            </div>
        `;
        return;
    }

    const paymentMethods = {
        qris: 'QRIS',
        bank_transfer: 'Transfer Bank',
        cash: 'Tunai (Cash)'
    };

    const serviceTypes = {
        dine_in: 'Makan di Tempat',
        take_away: 'Bawa Pulang',
        after_event: 'Diambil Setelah Kegiatan'
    };

    // Status awal seluruh tenant.
    // Nantinya data ini akan berasal dari database.
    const orderStatuses = {
        waiting_payment: {
            label: 'Menunggu Pembayaran',
            step: 0
        },
        waiting_confirmation: {
            label: 'Menunggu Konfirmasi',
            step: 1
        },
        preparing: {
            label: 'Sedang Diproses',
            step: 2
        },
        ready: {
            label: 'Siap Diambil',
            step: 3
        },
        completed: {
            label: 'Pesanan Selesai',
            step: 4
        }
    };

    const steps = [
        'Pesanan Dibuat',
        'Dikonfirmasi',
        'Sedang Diproses',
        'Siap Diambil'
    ];

    const statusLabel = (status) => {
        return orderStatuses[status]?.label || 'Menunggu Pembayaran';
    };

    const currentStatus = (tenant) => {
        return orderStatuses[tenant.status] || orderStatuses.waiting_payment;
    };

    const paymentStatus = confirmation.payment_status || 'pending';

    document.getElementById('parentOrderNumber').textContent =
        confirmation.order_number || '-';

    document.getElementById('paymentMethod').textContent =
        paymentMethods[pendingOrder.payment_method] || '-';

    document.getElementById('serviceType').textContent =
        serviceTypes[pendingOrder.service_type] || '-';

    document.getElementById('tenantCount').textContent =
        `${confirmation.tenant_orders?.length || 0} Tenant`;

    document.getElementById('grandTotal').textContent =
        formatRupiah(confirmation.grand_total);

    const mainStatus = document.getElementById('mainOrderStatus');

    if (paymentStatus === 'paid') {
        mainStatus.textContent = 'Pembayaran Berhasil';
        mainStatus.classList.add('is-success');
    } else {
        mainStatus.textContent = 'Menunggu Pembayaran';
    }

    if (pendingOrder.service_type === 'after_event') {
        const schedule = document.getElementById('pickupScheduleContainer');

        schedule.hidden = false;

        document.getElementById('pickupSchedule').textContent =
            `${pendingOrder.pickup_date || '-'} · ${pendingOrder.pickup_time || '-'}`;
    }

    const renderTimeline = (status) => {
        const current = currentStatus(status);
        const currentStep = current.step;

        return `
            <div class="tracking-timeline">
                ${steps.map((step, index) => {
                    const completed = index < currentStep;
                    const active = index === currentStep;

                    return `
                        <div class="timeline-step ${
                            completed ? 'completed' : ''
                        } ${active ? 'active' : ''}">
                            <div class="timeline-icon">
                                <i class="fa-solid ${
                                    completed
                                        ? 'fa-check'
                                        : active
                                            ? 'fa-circle'
                                            : 'fa-minus'
                                }"></i>
                            </div>
                            <span>${step}</span>
                        </div>
                    `;
                }).join('')}
            </div>
        `;
    };

    const renderTenant = (tenant) => {
        const card = document.createElement('article');
        card.className = 'tracking-tenant-card';

        const header = document.createElement('div');
        header.className = 'tracking-tenant-header';

        const tenantInfo = document.createElement('div');

        const name = document.createElement('div');
        name.className = 'tracking-tenant-name';
        name.textContent = tenant.tenant_name;

        const number = document.createElement('span');
        number.className = 'tracking-tenant-number';
        number.textContent = `No. Pesanan: ${tenant.order_number}`;

        tenantInfo.append(name, number);

        const badge = document.createElement('span');
        badge.className = 'tracking-status-badge';
        badge.textContent = statusLabel(tenant.status);

        if (tenant.status === 'completed') {
            badge.classList.add('is-success');
        }

        header.append(tenantInfo, badge);

        const body = document.createElement('div');
        body.className = 'tracking-tenant-body';

        body.insertAdjacentHTML(
            'beforeend',
            renderTimeline(tenant.status)
        );

        const productHeading = document.createElement('div');
        productHeading.className = 'tracking-product-heading';
        productHeading.textContent = 'Rincian Produk';

        const productList = document.createElement('div');
        productList.className = 'tracking-product-list';

        (tenant.products || []).forEach((product) => {
            const item = document.createElement('div');
            item.className = 'tracking-product-item';

            const productName = document.createElement('span');
            productName.textContent =
                `${product.name} × ${product.quantity}`;

            const productPrice = document.createElement('strong');
            productPrice.textContent = formatRupiah(
                Number(product.price) * Number(product.quantity)
            );

            item.append(productName, productPrice);
            productList.appendChild(item);
        });

        const subtotal = document.createElement('div');
        subtotal.className = 'tracking-subtotal';

        const subtotalLabel = document.createElement('span');
        subtotalLabel.textContent = 'Subtotal Tenant';

        const subtotalValue = document.createElement('strong');
        subtotalValue.textContent = formatRupiah(tenant.subtotal);

        subtotal.append(subtotalLabel, subtotalValue);

        const pickupCode = document.createElement('div');
        pickupCode.className = 'tracking-pickup-code';

        const pickupLabel = document.createElement('span');
        pickupLabel.textContent = 'Kode Pengambilan';

        const pickupValue = document.createElement('strong');
        pickupValue.textContent = tenant.pickup_code || '-';

        const pickupDescription = document.createElement('small');
        pickupDescription.textContent =
            'Tunjukkan kode ini kepada pegawai tenant saat mengambil pesanan.';

        pickupCode.append(
            pickupLabel,
            pickupValue,
            pickupDescription
        );

        body.append(
            productHeading,
            productList,
            subtotal,
            pickupCode
        );

        card.append(header, body);

        return card;
    };

    const renderStatus = () => {
        trackingList.innerHTML = '';

        (confirmation.tenant_orders || []).forEach((tenant) => {
            // Status default sampai backend menyediakan status aktual.
            if (!tenant.status) {
                tenant.status = 'waiting_payment';
            }

            trackingList.appendChild(renderTenant(tenant));
        });
    };

    renderStatus();

    document.getElementById('refreshStatus').addEventListener('click', () => {
        renderStatus();
    });
});