document.addEventListener('DOMContentLoaded', () => {

    const cartContainer = document.getElementById('cartTenantGroups');
    const cartEmpty = document.getElementById('cartEmpty');
    const cartSummary = document.getElementById('cartSummary');

    const cartItemCount = document.getElementById('cartItemCount');
    const summaryQuantity = document.getElementById('summaryQuantity');
    const summaryTenantCount = document.getElementById('summaryTenantCount');
    const summaryTotal = document.getElementById('summaryTotal');

    const checkoutButton = document.getElementById('checkoutButton');

    const formatRupiah = amount => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount);
    };

    function getCart() {
        return JSON.parse(
            sessionStorage.getItem('kantin_order_cart') || '[]'
        );
    }

    function saveCart(cart) {
        sessionStorage.setItem(
            'kantin_order_cart',
            JSON.stringify(cart)
        );
    }

    function renderCart() {

        const cart = getCart();

        cartContainer.innerHTML = '';

        if (cart.length === 0) {
            cartEmpty.classList.remove('d-none');
            cartSummary.classList.add('d-none');

            cartItemCount.textContent = '0 produk';
            return;
        }

        cartEmpty.classList.add('d-none');
        cartSummary.classList.remove('d-none');

        // Group products by tenant.
        const groupedCart = {};

        cart.forEach(item => {

            const tenantId = item.tenant_id;

            if (!groupedCart[tenantId]) {
                groupedCart[tenantId] = {
                    name: item.tenant_name,
                    items: []
                };
            }

            groupedCart[tenantId].items.push(item);
        });

        let totalQuantity = 0;
        let totalPrice = 0;

        Object.entries(groupedCart).forEach(([tenantId, group]) => {

            let tenantSubtotal = 0;
            let tenantQuantity = 0;

            const groupElement = document.createElement('div');
            groupElement.className = 'cart-tenant-group';

            const heading = document.createElement('div');
            heading.className = 'cart-tenant-heading';

            heading.innerHTML = `
                <div class="cart-tenant-icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h5></h5>
                    <small></small>
                </div>
                <span class="cart-tenant-subtotal"></span>
            `;

            heading.querySelector('h5').textContent = group.name;
            heading.querySelector('small').textContent =
                `${group.items.length} jenis produk`;

            groupElement.appendChild(heading);

            group.items.forEach(item => {

                const subtotal = item.price * item.quantity;

                tenantSubtotal += subtotal;
                tenantQuantity += item.quantity;

                const productElement = document.createElement('div');
                productElement.className = 'cart-product';

                const image = document.createElement('div');
                image.className = 'cart-product-image';

                const img = document.createElement('img');
                img.src = item.image || '/img-dummy/supermarket.png';
                img.alt = item.name;

                image.appendChild(img);

                const info = document.createElement('div');
                info.className = 'cart-product-info';

                const name = document.createElement('h6');
                name.textContent = item.name;

                const price = document.createElement('p');
                price.textContent = formatRupiah(item.price);

                info.append(name, price);

                const actions = document.createElement('div');
                actions.className = 'cart-product-actions';

                const quantity = document.createElement('div');
                quantity.className = 'quantity-control';

                const minus = document.createElement('button');
                minus.type = 'button';
                minus.textContent = '−';
                minus.addEventListener('click', () => {
                    updateQuantity(item.id, tenantId, -1);
                });

                const quantityValue = document.createElement('span');
                quantityValue.textContent = item.quantity;

                const plus = document.createElement('button');
                plus.type = 'button';
                plus.textContent = '+';
                plus.addEventListener('click', () => {
                    updateQuantity(item.id, tenantId, 1);
                });

                quantity.append(minus, quantityValue, plus);

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'remove-cart-btn';
                remove.innerHTML = '<i class="fa-solid fa-trash"></i>';
                remove.title = 'Hapus produk';

                remove.addEventListener('click', () => {
                    removeProduct(item.id, tenantId);
                });

                actions.append(quantity, remove);

                productElement.append(image, info, actions);

                groupElement.appendChild(productElement);

            });

            heading.querySelector('.cart-tenant-subtotal').textContent =
                formatRupiah(tenantSubtotal);

            cartContainer.appendChild(groupElement);

            totalQuantity += tenantQuantity;
            totalPrice += tenantSubtotal;

        });

        cartItemCount.textContent = `${totalQuantity} produk`;

        summaryQuantity.textContent = totalQuantity;
        summaryTenantCount.textContent =
            Object.keys(groupedCart).length;

        summaryTotal.textContent = formatRupiah(totalPrice);
    }

    function updateQuantity(productId, tenantId, change) {

        const cart = getCart();

        const product = cart.find(item =>
            item.id === productId &&
            item.tenant_id === tenantId
        );

        if (!product) return;

        product.quantity += change;

        if (product.quantity <= 0) {
            removeProduct(productId, tenantId);
            return;
        }

        saveCart(cart);
        renderCart();
    }

    function removeProduct(productId, tenantId) {

        let cart = getCart();

        cart = cart.filter(item =>
            !(item.id === productId && item.tenant_id === tenantId)
        );

        saveCart(cart);
        renderCart();
    }

    checkoutButton.addEventListener('click', () => {

        const cart = getCart();

        if (cart.length === 0) return;

        window.location.href = '/pesan/checkout';

    });

    renderCart();

});