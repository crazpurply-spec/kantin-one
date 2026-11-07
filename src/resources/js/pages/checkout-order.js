document.addEventListener('DOMContentLoaded', () => {

    const cart = JSON.parse(
        sessionStorage.getItem('kantin_order_cart') || '[]'
    );

    const service = JSON.parse(
        sessionStorage.getItem('kantin_order_service') || 'null'
    );

    const orderItems = document.getElementById('checkoutOrderItems');
    const tenantSubtotals = document.getElementById('checkoutTenantSubtotals');

    const form = document.getElementById('checkoutForm');
    const placeOrderButton = document.getElementById('placeOrderButton');

    const formatRupiah = amount => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount);
    };

    function groupCartItems() {

        const groups = {};

        cart.forEach(item => {

            if (!groups[item.tenant_id]) {
                groups[item.tenant_id] = {
                    name: item.tenant_name,
                    items: [],
                    subtotal: 0
                };
            }

            groups[item.tenant_id].items.push(item);

            groups[item.tenant_id].subtotal +=
                item.price * item.quantity;

        });

        return groups;
    }

    function renderOrder() {

        if (!cart.length) {
            window.location.href = '/pesan/keranjang';
            return;
        }

        const groups = groupCartItems();

        let totalQuantity = 0;
        let grandTotal = 0;

        Object.entries(groups).forEach(([tenantId, group]) => {

            const tenantGroup = document.createElement('div');
            tenantGroup.className = 'checkout-tenant-group';

            const title = document.createElement('div');
            title.className = 'checkout-tenant-title';

            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-store';

            const tenantName = document.createElement('span');
            tenantName.textContent = group.name;

            title.append(icon, tenantName);
            tenantGroup.appendChild(title);

            group.items.forEach(item => {

                const row = document.createElement('div');
                row.className = 'checkout-product-row';

                const image = document.createElement('img');
                image.src = item.image || '/img-dummy/supermarket.png';
                image.alt = item.name;

                const info = document.createElement('div');
                info.className = 'checkout-product-info';

                const name = document.createElement('h6');
                name.textContent = item.name;

                const quantity = document.createElement('small');
                quantity.textContent =
                    `${item.quantity} × ${formatRupiah(item.price)}`;

                info.append(name, quantity);

                const price = document.createElement('strong');
                price.className = 'checkout-product-price';
                price.textContent =
                    formatRupiah(item.price * item.quantity);

                row.append(image, info, price);
                tenantGroup.appendChild(row);

                totalQuantity += item.quantity;

            });

            orderItems.appendChild(tenantGroup);

            const subtotal = document.createElement('div');
            subtotal.className = 'checkout-tenant-subtotal';

            const name = document.createElement('span');
            name.textContent = group.name;

            const amount = document.createElement('strong');
            amount.textContent = formatRupiah(group.subtotal);

            subtotal.append(name, amount);
            tenantSubtotals.appendChild(subtotal);

            grandTotal += group.subtotal;

        });

        document.getElementById('checkoutTenantCount').textContent =
            Object.keys(groups).length;

        document.getElementById('checkoutProductCount').textContent =
            totalQuantity;

        document.getElementById('checkoutGrandTotal').textContent =
            formatRupiah(grandTotal);

    }

    function renderService() {

        if (!service) return;

        const serviceNames = {
            dine_in: 'Dine In',
            take_away: 'Take Away',
            after_event: 'Diambil Setelah Kegiatan'
        };

        const serviceDescriptions = {
            dine_in: 'Pesanan dinikmati di area kantin.',
            take_away: 'Pesanan dibawa pulang.',
            after_event: 'Pesanan diambil sesuai jadwal yang ditentukan.'
        };

        const serviceIcons = {
            dine_in: 'fa-chair',
            take_away: 'fa-bag-shopping',
            after_event: 'fa-calendar-check'
        };

        const type = service.service_type;

        document.getElementById('checkoutServiceName').textContent =
            serviceNames[type] || 'Dine In';

        document.getElementById('checkoutServiceDescription').textContent =
            serviceDescriptions[type] || '';

        document.getElementById('checkoutServiceIcon').className =
            `fa-solid ${serviceIcons[type] || 'fa-chair'}`;

        const tableInformation =
            document.getElementById('tableInformation');

        const pickupInformation =
            document.getElementById('pickupInformation');

        if (type === 'dine_in') {

            tableInformation.classList.remove('d-none');

        } else {

            tableInformation.classList.add('d-none');

        }

        if (type === 'after_event') {

            pickupInformation.classList.remove('d-none');

            document.getElementById('checkoutPickupDate').value =
                service.pickup_date || '';

            document.getElementById('checkoutPickupTime').value =
                service.pickup_time || '';

        }

    }

    form.addEventListener('submit', event => {

        event.preventDefault();

        if (!cart.length) return;

        const paymentMethod = document.querySelector(
            'input[name="payment_method"]:checked'
        );

        if (!paymentMethod) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Metode Pembayaran',
                text: 'Silakan pilih metode pembayaran terlebih dahulu.'
            });
            return;
        }

        const pickupDate =
            document.getElementById('checkoutPickupDate').value;

        const pickupTime =
            document.getElementById('checkoutPickupTime').value;

        if (
            service?.service_type === 'after_event' &&
            (!pickupDate || !pickupTime)
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Jadwal Belum Lengkap',
                text: 'Silakan lengkapi tanggal dan waktu pengambilan.'
            });
            return;
        }

        const orderData = {
            service_type: service?.service_type || 'dine_in',
            pickup_date: pickupDate || null,
            pickup_time: pickupTime || null,
            payment_method: paymentMethod.value,
            notes: document.getElementById('orderNotes').value,
            cart: cart,
            created_at: new Date().toISOString()
        };

        sessionStorage.setItem(
            'kantin_pending_order',
            JSON.stringify(orderData)
        );

        window.location.href = '/pesan/konfirmasi';

    });

    renderOrder();
    renderService();

});