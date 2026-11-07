document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('productSearch');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');

    const productCount = document.getElementById('productCount');
    const productEmpty = document.getElementById('productEmpty');

    const cartCount = document.getElementById('cartCount');
    const floatingCart = document.getElementById('floatingCart');
    const floatingCartTotal = document.getElementById('floatingCartTotal');

    const tenant = {
        id: 'tenant-warung-nusantara',
        name: 'Warung Nusantara'
    };

    let activeCategory = 'all';

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

    function updateCartDisplay() {
        const cart = getCart();

        const totalItems = cart.reduce(
            (total, item) => total + item.quantity,
            0
        );

        const totalPrice = cart.reduce(
            (total, item) => total + (item.price * item.quantity),
            0
        );

        cartCount.textContent = totalItems;
        floatingCartTotal.textContent = formatRupiah(totalPrice);

        floatingCart.classList.toggle(
            'd-none',
            totalItems === 0
        );
    }

    function filterProducts() {
        const keyword = searchInput.value.toLowerCase().trim();

        let visibleCount = 0;

        productCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const category = card.dataset.category;

            const matchesName = name.includes(keyword);

            const matchesCategory =
                activeCategory === 'all' ||
                category === activeCategory;

            const visible = matchesName && matchesCategory;

            card.classList.toggle('d-none', !visible);

            if (visible) visibleCount++;
        });

        productCount.textContent = `${visibleCount} produk ditemukan`;

        productEmpty.classList.toggle(
            'd-none',
            visibleCount > 0
        );
    }

    searchInput.addEventListener('input', filterProducts);

    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            categoryButtons.forEach(item => {
                item.classList.remove('active');
            });

            button.classList.add('active');

            activeCategory = button.dataset.category;

            filterProducts();
        });
    });

    // ADD PRODUCT TO CART
    document.querySelectorAll('.add-cart-btn').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.product-card');

            const product = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: Number(card.dataset.price),
                tenant_id: tenant.id,
                tenant_name: tenant.name,
                quantity: 1
            };

            const cart = getCart();

            const existingProduct = cart.find(item =>
                item.id === product.id &&
                item.tenant_id === product.tenant_id
            );

            if (existingProduct) {
                existingProduct.quantity++;
            } else {
                cart.push(product);
            }

            saveCart(cart);
            updateCartDisplay();

            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `${product.name} ditambahkan`,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // CART BUTTON
    floatingCart.addEventListener('click', () => {
        window.location.href = '/pesan/keranjang';
    });

    document.getElementById('openCart').addEventListener('click', () => {
        window.location.href = '/pesan/keranjang';
    });

    updateCartDisplay();
});