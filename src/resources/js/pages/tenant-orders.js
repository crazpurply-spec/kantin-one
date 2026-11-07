document.addEventListener('DOMContentLoaded', () => {
    const CONFIRMATION_KEY = 'kantin_order_confirmation';
    const PENDING_KEY = 'kantin_pending_order';

    const getData = (key) => {
        try {
            return JSON.parse(sessionStorage.getItem(key));
        } catch {
            return null;
        }
    };

    const saveConfirmation = (data) => {
        sessionStorage.setItem(
            CONFIRMATION_KEY,
            JSON.stringify(data)
        );
    };

    const formatRupiah = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount || 0);
    };

    const confirmation = getData(CONFIRMATION_KEY);
    const pendingOrder = getData(PENDING_KEY);

    const orderList = document.getElementById('tenantOrderList');

    const statusConfig = {
        waiting_confirmation: {
            label: 'Menunggu Konfirmasi',
            badge: 'waiting'
        },
        preparing: {
            label: 'Sedang Diproses',
            badge: 'processing'
        },
        ready: {
            label: 'Siap Diambil',
            badge: 'ready'
        },
        completed: {
            label: 'Selesai',
            badge: 'completed'
        }
    };

    let activeFilter = 'all';
    let searchQuery = '';

    // Untuk simulasi, seluruh pesanan yang ditemukan
    // dianggap berasal dari tenant yang sedang login.
    const getOrders = () => {
        if (!confirmation || !pendingOrder) {
            return [];
        }

        return (confirmation.tenant_orders || []).map((tenant) => {
            if (!tenant.status) {
                tenant.status = 'waiting_confirmation';
            }

            return {
                ...tenant,
                parent_order_number: confirmation.order_number,
                payment_method: pendingOrder.payment_method,
                service_type: pendingOrder.service_type,
                pickup_date: pendingOrder.pickup_date,
                pickup_time: pendingOrder.pickup_time
            };
        });
    };

    const updateStatistics = (orders) => {
        const count = (status) =>
            orders.filter((order) => order.status === status).length;

        document.getElementById('countWaiting').textContent =
            count('waiting_confirmation');

        document.getElementById('countProcessing').textContent =
            count('preparing');

        document.getElementById('countReady').textContent =
            count('ready');

        document.getElementById('countCompleted').textContent =
            count('completed');
    };

    const createOrderCard = (order) => {
        const config = statusConfig[order.status];

        const card = document.createElement('article');
        card.className = 'employee-order-card';

        const header = document.createElement('div');
        header.className = 'employee-order-header';

        const headerInfo = document.createElement('div');

        const orderNumber = document.createElement('div');
        orderNumber.className = 'employee-order-number';
        orderNumber.textContent = order.order_number;

        const parentNumber = document.createElement('span');
        parentNumber.className = 'employee-order-parent';
        parentNumber.textContent =
            `Pesanan Utama: ${order.parent_order_number}`;

        headerInfo.append(orderNumber, parentNumber);

        const badge = document.createElement('span');
        badge.className = `employee-order-badge ${config.badge}`;
        badge.textContent = config.label;

        header.append(headerInfo, badge);

        const body = document.createElement('div');
        body.className = 'employee-order-body';

        const information = document.createElement('div');
        information.className = 'employee-order-info';

        const infoItems = [
            ['Kode Pengambilan', order.pickup_code],
            ['Metode Pembayaran', {
                qris: 'QRIS',
                bank_transfer: 'Transfer Bank',
                cash: 'Tunai'
            }[order.payment_method] || '-'],
            ['Jenis Layanan', {
                dine_in: 'Makan di Tempat',
                take_away: 'Bawa Pulang',
                after_event: 'Diambil Setelah Kegiatan'
            }[order.service_type] || '-']
        ];

        infoItems.forEach(([label, value]) => {
            const item = document.createElement('div');

            const itemLabel = document.createElement('span');
            itemLabel.textContent = label;

            const itemValue = document.createElement('strong');
            itemValue.textContent = value || '-';

            item.append(itemLabel, itemValue);
            information.appendChild(item);
        });

        if (order.service_type === 'after_event') {
            const schedule = document.createElement('div');

            const label = document.createElement('span');
            label.textContent = 'Jadwal Pengambilan';

            const value = document.createElement('strong');
            value.textContent =
                `${order.pickup_date || '-'} ${order.pickup_time || ''}`;

            schedule.append(label, value);
            information.appendChild(schedule);
        }

        const productHeading = document.createElement('div');
        productHeading.className = 'employee-product-heading';
        productHeading.textContent = 'Daftar Produk';

        const productList = document.createElement('div');
        productList.className = 'employee-product-list';

        (order.products || []).forEach((product) => {
            const item = document.createElement('div');
            item.className = 'employee-product';

            const name = document.createElement('span');
            name.textContent = `${product.name} × ${product.quantity}`;

            const price = document.createElement('strong');
            price.textContent = formatRupiah(
                Number(product.price) * Number(product.quantity)
            );

            item.append(name, price);
            productList.appendChild(item);
        });

        const total = document.createElement('div');
        total.className = 'employee-order-total';

        const totalLabel = document.createElement('span');
        totalLabel.textContent = 'Subtotal Pesanan';

        const totalValue = document.createElement('strong');
        totalValue.textContent = formatRupiah(order.subtotal);

        total.append(totalLabel, totalValue);

        const code = document.createElement('div');
        code.className = 'employee-order-code';

        const codeLabel = document.createElement('span');
        codeLabel.textContent = 'Kode Pengambilan';

        const codeValue = document.createElement('strong');
        codeValue.textContent = order.pickup_code || '-';

        code.append(codeLabel, codeValue);

        const actions = document.createElement('div');
        actions.className = 'employee-order-actions';

        const addAction = (label, nextStatus, buttonClass) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = buttonClass;
            button.textContent = label;

            button.addEventListener('click', () => {
                updateOrderStatus(order.order_number, nextStatus);
            });

            actions.appendChild(button);
        };

        if (order.status === 'waiting_confirmation') {
            addAction(
                'Terima Pesanan',
                'preparing',
                'btn-order-primary'
            );
        } else if (order.status === 'preparing') {
            addAction(
                'Tandai Siap Diambil',
                'ready',
                'btn-order-primary'
            );
        } else if (order.status === 'ready') {
            addAction(
                'Konfirmasi Penyerahan',
                'completed',
                'btn-order-primary'
            );
        } else {
            const completedLabel = document.createElement('span');
            completedLabel.textContent = 'Pesanan telah diselesaikan.';
            completedLabel.style.color = '#198754';
            completedLabel.style.fontSize = '12px';
            completedLabel.style.fontWeight = '700';

            actions.appendChild(completedLabel);
        }

        body.append(
            information,
            productHeading,
            productList,
            total,
            code,
            actions
        );

        card.append(header, body);

        return card;
    };

    const renderOrders = () => {
        const orders = getOrders();

        updateStatistics(orders);

        const filteredOrders = orders.filter((order) => {
            const matchesStatus =
                activeFilter === 'all' ||
                order.status === activeFilter;

            const matchesSearch =
                order.order_number.toLowerCase().includes(searchQuery) ||
                order.parent_order_number.toLowerCase().includes(searchQuery);

            return matchesStatus && matchesSearch;
        });

        orderList.innerHTML = '';

        if (filteredOrders.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'employee-order-empty';

            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-receipt';

            const title = document.createElement('strong');
            title.textContent = 'Belum Ada Pesanan';

            const description = document.createElement('p');
            description.textContent =
                'Pesanan yang sesuai dengan filter akan muncul di sini.';

            empty.append(icon, title, description);
            orderList.appendChild(empty);
            return;
        }

        filteredOrders.forEach((order) => {
            orderList.appendChild(createOrderCard(order));
        });
    };

    const updateOrderStatus = (orderNumber, nextStatus) => {
        const data = getData(CONFIRMATION_KEY);

        if (!data) return;

        const tenant = (data.tenant_orders || []).find(
            (item) => item.order_number === orderNumber
        );

        if (!tenant) return;

        tenant.status = nextStatus;

        saveConfirmation(data);
        renderOrders();
    };

    document.querySelectorAll('#orderFilters button').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('#orderFilters button')
                .forEach((item) => item.classList.remove('active'));

            button.classList.add('active');
            activeFilter = button.dataset.filter;

            renderOrders();
        });
    });

    document.getElementById('searchOrder').addEventListener('input', (event) => {
        searchQuery = event.target.value.trim().toLowerCase();
        renderOrders();
    });

    document.getElementById('refreshOrders').addEventListener('click', () => {
        renderOrders();
    });

    renderOrders();
});