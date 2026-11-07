document.addEventListener('DOMContentLoaded', () => {
    const CART_KEY = 'kantin_order_cart';
    const PENDING_KEY = 'kantin_pending_order';
    const CONFIRMATION_KEY = 'kantin_order_confirmation';

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

    const generateCode = (prefix) => {
        const random = Math.floor(100000 + Math.random() * 900000);
        return `${prefix}-${random}`;
    };

    const pendingOrder = getData(PENDING_KEY);

    if (!pendingOrder) {
        window.location.href = '/pesan';
        return;
    }

    let confirmation = getData(CONFIRMATION_KEY);

    // Buat konfirmasi hanya satu kali agar kode tidak berubah
    // ketika halaman di-refresh.
    if (!confirmation) {
        const cart = pendingOrder.cart || [];

        const groupedTenants = {};

        cart.forEach((product) => {
            const tenantId = product.tenant_id || 'tenant-unknown';

            if (!groupedTenants[tenantId]) {
                groupedTenants[tenantId] = {
                    tenant_id: tenantId,
                    tenant_name: product.tenant_name || 'Tenant',
                    products: [],
                    subtotal: 0,
                    order_number: generateCode('TN'),
                    pickup_code: generateCode('PK')
                };
            }

            groupedTenants[tenantId].products.push(product);

            groupedTenants[tenantId].subtotal +=
                Number(product.price || 0) *
                Number(product.quantity || 1);
        });

        const tenantOrders = Object.values(groupedTenants);

        const grandTotal = tenantOrders.reduce(
            (total, tenant) => total + tenant.subtotal,
            0
        );

        confirmation = {
            order_number: generateCode('KN'),
            payment_status: 'pending',
            tenant_orders: tenantOrders,
            grand_total: grandTotal,
            created_at: new Date().toISOString()
        };

        sessionStorage.setItem(
            CONFIRMATION_KEY,
            JSON.stringify(confirmation)
        );

        // Keranjang dikosongkan setelah konfirmasi berhasil dibuat.
        sessionStorage.removeItem(CART_KEY);
    }

    const paymentMethods = {
        qris: {
            name: 'QRIS',
            instruction:
                'Silakan lakukan pembayaran melalui QRIS. ' +
                'Fitur pembayaran QRIS akan tersedia setelah integrasi payment gateway.'
        },
        bank_transfer: {
            name: 'Transfer Bank',
            instruction:
                'Silakan lakukan transfer sesuai total pembayaran. ' +
                'Informasi rekening tujuan akan tersedia setelah integrasi pembayaran.'
        },
        cash: {
            name: 'Tunai (Cash)',
            instruction:
                'Silakan lakukan pembayaran tunai sesuai total pesanan ' +
                'ketika melakukan pengambilan pesanan.'
        }
    };

    const serviceTypes = {
        dine_in: 'Makan di Tempat',
        take_away: 'Bawa Pulang',
        after_event: 'Diambil Setelah Kegiatan'
    };

    const payment = paymentMethods[pendingOrder.payment_method] || {
        name: 'Tidak diketahui',
        instruction: 'Informasi pembayaran belum tersedia.'
    };

    const tenantOrders = confirmation.tenant_orders || [];

    document.getElementById('parentOrderNumber').textContent =
        confirmation.order_number;

    document.getElementById('paymentMethod').textContent =
        payment.name;

    document.getElementById('serviceType').textContent =
        serviceTypes[pendingOrder.service_type] || 'Tidak diketahui';

    document.getElementById('tenantCount').textContent =
        `${tenantOrders.length} Tenant`;

    document.getElementById('grandTotal').textContent =
        formatRupiah(confirmation.grand_total);

    document.getElementById('paymentInstructionText').textContent =
        payment.instruction;

    // Jadwal pengambilan untuk layanan setelah kegiatan.
    if (pendingOrder.service_type === 'after_event') {
        const scheduleRow = document.getElementById('pickupScheduleRow');

        scheduleRow.hidden = false;

        document.getElementById('pickupSchedule').textContent =
            `${pendingOrder.pickup_date || '-'} · ${pendingOrder.pickup_time || '-'}`;
    }

    // Status pembayaran masih menunggu karena belum ada
    // integrasi payment gateway.
    document.getElementById('paymentStatus').textContent =
        'Menunggu Pembayaran';

    document.getElementById('paymentBadge').textContent =
        'Belum Dibayar';

    document.getElementById('paymentDescription').textContent =
        pendingOrder.payment_method === 'cash'
            ? 'Pembayaran dilakukan secara tunai saat pengambilan pesanan.'
            : 'Selesaikan pembayaran untuk melanjutkan proses pesanan.';

    // Tampilkan pesanan setiap tenant.
    const tenantOrdersContainer =
        document.getElementById('tenantOrders');

    tenantOrdersContainer.innerHTML = '';

    tenantOrders.forEach((tenant) => {
        const card = document.createElement('div');
        card.className = 'tenant-order-card';

        const header = document.createElement('div');
        header.className = 'tenant-order-header';

        const headerInfo = document.createElement('div');

        const tenantName = document.createElement('div');
        tenantName.className = 'tenant-order-name';
        tenantName.textContent = tenant.tenant_name;

        const orderNumber = document.createElement('span');
        orderNumber.className = 'tenant-order-number';
        orderNumber.textContent = `No. Pesanan: ${tenant.order_number}`;

        headerInfo.append(tenantName, orderNumber);

        const status = document.createElement('span');
        status.className = 'tenant-order-status';
        status.textContent = 'Menunggu Pembayaran';

        header.append(headerInfo, status);

        const body = document.createElement('div');
        body.className = 'tenant-order-body';

        const productList = document.createElement('div');
        productList.className = 'tenant-product-list';

        tenant.products.forEach((product) => {
            const item = document.createElement('div');
            item.className = 'tenant-product-item';

            const name = document.createElement('span');
            name.className = 'tenant-product-name';
            name.textContent =
                `${product.name} × ${product.quantity}`;

            const price = document.createElement('strong');
            price.className = 'tenant-product-price';
            price.textContent = formatRupiah(
                Number(product.price) * Number(product.quantity)
            );

            item.append(name, price);
            productList.appendChild(item);
        });

        const subtotal = document.createElement('div');
        subtotal.className = 'tenant-subtotal';

        const subtotalLabel = document.createElement('span');
        subtotalLabel.textContent = 'Subtotal Tenant';

        const subtotalValue = document.createElement('strong');
        subtotalValue.textContent = formatRupiah(tenant.subtotal);

        subtotal.append(subtotalLabel, subtotalValue);

        const pickupCode = document.createElement('div');
        pickupCode.className = 'pickup-code-box';

        const pickupLabel = document.createElement('span');
        pickupLabel.textContent = 'Kode Pengambilan';

        const pickupValue = document.createElement('strong');
        pickupValue.textContent = tenant.pickup_code;

        pickupCode.append(pickupLabel, pickupValue);

        body.append(productList, subtotal, pickupCode);
        card.append(header, body);

        tenantOrdersContainer.appendChild(card);
    });
});