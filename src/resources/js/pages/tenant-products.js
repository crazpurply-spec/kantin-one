document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'kantin_tenant_product_availability';

    // Data simulasi produk tenant
    const defaultProducts = [
        {
            id: 'PRD001',
            name: 'Nasi Ayam Bakar',
            category: 'makanan',
            price: 18000,
            image: '/img-dummy/supermarket.png',
            available: true
        },
        {
            id: 'PRD002',
            name: 'Nasi Goreng Spesial',
            category: 'makanan',
            price: 20000,
            image: '/img-dummy/supermarket.png',
            available: true
        },
        {
            id: 'PRD003',
            name: 'Mie Goreng',
            category: 'makanan',
            price: 15000,
            image: '/img-dummy/supermarket.png',
            available: true
        },
        {
            id: 'PRD004',
            name: 'Es Teh Manis',
            category: 'minuman',
            price: 5000,
            image: '/img-dummy/supermarket.png',
            available: true
        },
        {
            id: 'PRD005',
            name: 'Es Jeruk',
            category: 'minuman',
            price: 8000,
            image: '/img-dummy/supermarket.png',
            available: false
        },
        {
            id: 'PRD006',
            name: 'Pisang Goreng',
            category: 'snack',
            price: 10000,
            image: '/img-dummy/supermarket.png',
            available: true
        },
        {
            id: 'PRD007',
            name: 'Soto Ayam',
            category: 'makanan',
            price: 17000,
            image: '/img-dummy/supermarket.png',
            available: false
        }
    ];

    let products = [...defaultProducts];

    try {
        const savedProducts = JSON.parse(
            sessionStorage.getItem(STORAGE_KEY) || 'null'
        );

        if (Array.isArray(savedProducts)) {
            products = defaultProducts.map(product => {
                const saved = savedProducts.find(
                    item => item.id === product.id
                );

                return saved
                    ? { ...product, available: Boolean(saved.available) }
                    : product;
            });
        }
    } catch (error) {
        console.error('Gagal membaca status produk:', error);
    }

    const tableBody = document.getElementById('productsTableBody');
    const searchInput = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const availabilityFilter = document.getElementById('availabilityFilter');
    const emptyState = document.getElementById('productsEmptyState');

    const categoryLabels = {
        makanan: 'Makanan',
        minuman: 'Minuman',
        snack: 'Snack'
    };

    const formatCurrency = amount => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount);
    };

    function saveProducts() {
        const data = products.map(product => ({
            id: product.id,
            available: product.available
        }));

        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    function updateStatistics() {
        const availableCount = products.filter(
            product => product.available
        ).length;

        document.getElementById('totalProducts').textContent =
            products.length;

        document.getElementById('availableProducts').textContent =
            availableCount;

        document.getElementById('unavailableProducts').textContent =
            products.length - availableCount;
    }

    function createProductRow(product) {
        const row = document.createElement('tr');

        // Product information
        const productCell = document.createElement('td');

        const productInfo = document.createElement('div');
        productInfo.className = 'product-table-info';

        const imageWrapper = document.createElement('div');
        imageWrapper.className = 'product-table-image';

        const image = document.createElement('img');
        image.src = product.image;
        image.alt = product.name;

        imageWrapper.appendChild(image);

        const productText = document.createElement('div');

        const productName = document.createElement('strong');
        productName.textContent = product.name;

        const productId = document.createElement('small');
        productId.textContent = product.id;

        productText.append(productName, productId);
        productInfo.append(imageWrapper, productText);
        productCell.appendChild(productInfo);

        // Category
        const categoryCell = document.createElement('td');

        const categoryBadge = document.createElement('span');
        categoryBadge.className = 'product-category';
        categoryBadge.textContent =
            categoryLabels[product.category] || product.category;

        categoryCell.appendChild(categoryBadge);

        // Price
        const priceCell = document.createElement('td');
        priceCell.className = 'product-price';
        priceCell.textContent = formatCurrency(product.price);

        // Availability
        const statusCell = document.createElement('td');

        const statusBadge = document.createElement('span');
        statusBadge.className = product.available
            ? 'availability-badge available'
            : 'availability-badge unavailable';

        const statusIcon = document.createElement('i');
        statusIcon.className = product.available
            ? 'fa-solid fa-circle-check'
            : 'fa-solid fa-circle-xmark';

        const statusText = document.createElement('span');
        statusText.textContent = product.available
            ? 'Tersedia'
            : 'Tidak Tersedia';

        statusBadge.append(statusIcon, statusText);
        statusCell.appendChild(statusBadge);

        // Toggle action
        const actionCell = document.createElement('td');

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = product.available
            ? 'availability-toggle active'
            : 'availability-toggle';

        toggle.setAttribute(
            'aria-label',
            product.available
                ? `Nonaktifkan ${product.name}`
                : `Aktifkan ${product.name}`
        );

        toggle.setAttribute('aria-pressed', String(product.available));

        const track = document.createElement('span');
        track.className = 'toggle-track';

        const label = document.createElement('span');
        label.className = 'toggle-label';
        label.textContent = product.available ? 'Aktif' : 'Nonaktif';

        toggle.append(track, label);

        toggle.addEventListener('click', () => {
            const targetProduct = products.find(
                item => item.id === product.id
            );

            if (!targetProduct) return;

            targetProduct.available = !targetProduct.available;

            saveProducts();
            renderProducts();
        });

        actionCell.appendChild(toggle);

        row.append(
            productCell,
            categoryCell,
            priceCell,
            statusCell,
            actionCell
        );

        return row;
    }

    function renderProducts() {
        const keyword = searchInput.value.trim().toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedAvailability = availabilityFilter.value;

        const filteredProducts = products.filter(product => {
            const matchesKeyword = product.name
                .toLowerCase()
                .includes(keyword);

            const matchesCategory =
                selectedCategory === 'all' ||
                product.category === selectedCategory;

            const matchesAvailability =
                selectedAvailability === 'all' ||
                (selectedAvailability === 'available' && product.available) ||
                (selectedAvailability === 'unavailable' && !product.available);

            return (
                matchesKeyword &&
                matchesCategory &&
                matchesAvailability
            );
        });

        tableBody.replaceChildren();

        filteredProducts.forEach(product => {
            tableBody.appendChild(createProductRow(product));
        });

        emptyState.hidden = filteredProducts.length > 0;

        document.getElementById('productsResultCount').textContent =
            `Menampilkan ${filteredProducts.length} dari ${products.length} produk`;

        updateStatistics();
    }

    searchInput.addEventListener('input', renderProducts);
    categoryFilter.addEventListener('change', renderProducts);
    availabilityFilter.addEventListener('change', renderProducts);

    renderProducts();
});