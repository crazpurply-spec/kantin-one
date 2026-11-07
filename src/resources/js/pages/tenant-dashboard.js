document.addEventListener('DOMContentLoaded', () => {
    const confirmation = JSON.parse(
        sessionStorage.getItem('kantin_order_confirmation') || 'null'
    );

    const pendingOrder = JSON.parse(
        sessionStorage.getItem('kantin_pending_order') || 'null'
    );

    const tenantOrders = confirmation?.tenant_orders || [];

    const statusLabels = {
        waiting: 'Menunggu Konfirmasi',
        pending: 'Menunggu Konfirmasi',
        processing: 'Sedang Diproses',
        preparing: 'Sedang Diproses',
        ready: 'Siap Diambil',
        completed: 'Selesai'
    };

    const statusClasses = {
        waiting: 'status-waiting',
        pending: 'status-waiting',
        processing: 'status-processing',
        preparing: 'status-processing',
        ready: 'status-ready',
        completed: 'status-completed'
    };

    const getStatus = (order) => {
        return String(order.status || 'waiting').toLowerCase();
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(Number(amount) || 0);
    };

    const getOrderTotal = (order) => {
        if (order.subtotal !== undefined) {
            return Number(order.subtotal) || 0;
        }

        if (order.total !== undefined) {
            return Number(order.total) || 0;
        }

        return (order.products || []).reduce((total, product) => {
            return total +
                (Number(product.price) * Number(product.quantity || 1));
        }, 0);
    };

    // Statistik pesanan
    const counts = {
        waiting: 0,
        processing: 0,
        ready: 0,
        completed: 0
    };

    let totalSales = 0;

    tenantOrders.forEach((order) => {
        const status = getStatus(order);

        if (status === 'waiting' || status === 'pending') {
            counts.waiting++;
        } else if (status === 'processing' || status === 'preparing') {
            counts.processing++;
        } else if (status === 'ready') {
            counts.ready++;
        } else if (status === 'completed') {
            counts.completed++;
            totalSales += getOrderTotal(order);
        }
    });

    document.getElementById('waitingCount').textContent = counts.waiting;
    document.getElementById('processingCount').textContent = counts.processing;
    document.getElementById('readyCount').textContent = counts.ready;
    document.getElementById('completedCount').textContent = counts.completed;
    document.getElementById('todaySales').textContent = formatCurrency(totalSales);

    // Pesanan terbaru
    const recentOrdersContainer = document.getElementById('recentOrders');

    const recentOrders = [...tenantOrders].reverse().slice(0, 5);

    if (!recentOrders.length) {
        recentOrdersContainer.innerHTML = `
            <div class="dashboard-empty-state">
                <i class="fa-solid fa-receipt"></i>
                <p>Belum ada pesanan masuk.</p>
            </div>
        `;
        return;
    }

    recentOrdersContainer.innerHTML = '';

    recentOrders.forEach((order) => {
        const status = getStatus(order);
        const statusLabel = statusLabels[status] || 'Status Tidak Diketahui';
        const statusClass = statusClasses[status] || 'status-waiting';

        const orderNumber =
            order.order_number ||
            order.tenant_order_number ||
            'Pesanan';

        const pickupCode = order.pickup_code || '-';
        const total = getOrderTotal(order);

        const item = document.createElement('div');
        item.className = 'recent-order-item';

        const info = document.createElement('div');
        info.className = 'recent-order-info';

        const orderTitle = document.createElement('strong');
        orderTitle.textContent = orderNumber;

        const orderDescription = document.createElement('span');
        orderDescription.textContent = `Kode pengambilan: ${pickupCode}`;

        info.append(orderTitle, orderDescription);

        const meta = document.createElement('div');
        meta.className = 'recent-order-meta';

        const amount = document.createElement('strong');
        amount.textContent = formatCurrency(total);

        const badge = document.createElement('span');
        badge.className = `recent-order-status ${statusClass}`;
        badge.textContent = statusLabel;

        meta.append(amount, badge);
        item.append(info, meta);

        recentOrdersContainer.appendChild(item);
    });
});