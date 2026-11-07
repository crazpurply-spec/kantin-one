document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('tenantSearch');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const tenantCards = document.querySelectorAll('.tenant-card');

    const tenantCount = document.getElementById('tenantCount');
    const tenantEmpty = document.getElementById('tenantEmpty');

    let activeCategory = 'all';

    function filterTenants() {

        const keyword = searchInput.value.toLowerCase().trim();

        let visibleCount = 0;

        tenantCards.forEach(card => {

            const name = card.dataset.name;
            const category = card.dataset.category;

            const matchesName = name.includes(keyword);

            const matchesCategory =
                activeCategory === 'all' ||
                category === activeCategory;

            const visible = matchesName && matchesCategory;

            card.classList.toggle('d-none', !visible);

            if (visible) visibleCount++;

        });

        tenantCount.textContent = `${visibleCount} tenant ditemukan`;

        tenantEmpty.classList.toggle(
            'd-none',
            visibleCount > 0
        );
    }

    searchInput.addEventListener('input', filterTenants);

    categoryButtons.forEach(button => {

        button.addEventListener('click', () => {

            categoryButtons.forEach(item => {
                item.classList.remove('active');
            });

            button.classList.add('active');

            activeCategory = button.dataset.category;

            filterTenants();

        });

    });

    // Informasi jenis layanan dari halaman sebelumnya.
    const service = JSON.parse(
        sessionStorage.getItem('kantin_order_service') || 'null'
    );

    if (service) {

        const serviceNames = {
            dine_in: 'Dine In',
            take_away: 'Take Away',
            after_event: 'Diambil Setelah Kegiatan'
        };

        const serviceIcons = {
            dine_in: 'fa-chair',
            take_away: 'fa-bag-shopping',
            after_event: 'fa-calendar-check'
        };

        document.getElementById('serviceName').textContent =
            serviceNames[service.service_type] || 'Dine In';

        document.getElementById('serviceIcon').className =
            `fa-solid ${serviceIcons[service.service_type] || 'fa-chair'}`;

    }

});