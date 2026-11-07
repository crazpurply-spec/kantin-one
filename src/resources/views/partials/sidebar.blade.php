@php
    $role = auth()->user()->role ?? '';

    $menus = match ($role) {
        'super_admin', 'administrator' => [
            [
                'label' => 'Beranda',
                'icon' => 'fa-solid fa-house',
                'url' => '/dashboard',
                'active' => ['dashboard'],
            ],
            [
                'label' => 'Manajemen Pengguna',
                'icon' => 'fa-solid fa-users',
                'url' => route('users.index'),
                'active' => ['users*'],
            ],
            [
                'label' => 'Manajemen Tenant',
                'icon' => 'fa-solid fa-store',
                'url' => route('tenants.index'),
                'active' => ['tenants*'],
            ],
            [
                'label' => 'Manajemen Produk',
                'icon' => 'fa-solid fa-users',
                'url' => route('products.index'),
                'active' => ['products*'],
            ],
            [
                'label' => 'Aplikasi Pemesanan Pelanggan',
                'icon' => 'fa-solid fa-users',
                'url' => '#',
                'active' => ['orders.customer*'],
            ],
            [
                'label' => 'Aplikasi Monitoring Pesanan',
                'icon' => 'fa-solid fa-users',
                'url' => '#',
                'active' => ['orders.customer*'],
            ],
            [
                'label' => 'Aplikasi Verifikasi Pesanan',
                'icon' => 'fa-solid fa-users',
                'url' => '#',
                'active' => ['orders.customer*'],
            ],
            [
                'label' => 'Laporan',
                'icon' => 'fa-solid fa-chart-pie',
                'url' => '#',
                'active' => ['reports*'],
            ],
        ],

        'Pengelola Kantin', 'Kepala Pengelolaan Tenant' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'url' => '/dashboard',
                'active' => ['dashboard'],
            ],
            [
                'label' => 'Data Tenant',
                'icon' => 'fa-solid fa-store',
                'url' => '#',
                'active' => ['tenants*'],
            ],
            [
                'label' => 'Monitoring Tenant',
                'icon' => 'fa-solid fa-chart-line',
                'url' => '#',
                'active' => ['monitoring*'],
            ],
            [
                'label' => 'Laporan',
                'icon' => 'fa-solid fa-file-lines',
                'url' => '#',
                'active' => ['reports*'],
            ],
        ],

        'Kepala Tenant', 'Tenant' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'url' => '/dashboard',
                'active' => ['dashboard'],
            ],
            [
                'label' => 'Produk',
                'icon' => 'fa-solid fa-box',
                'url' => '#',
                'active' => ['products*'],
            ],
            [
                'label' => 'Persediaan',
                'icon' => 'fa-solid fa-boxes-stacked',
                'url' => '#',
                'active' => ['inventory*'],
            ],
            [
                'label' => 'Transaksi',
                'icon' => 'fa-solid fa-receipt',
                'url' => '#',
                'active' => ['transactions*'],
            ],
            [
                'label' => 'Laporan',
                'icon' => 'fa-solid fa-chart-column',
                'url' => '#',
                'active' => ['reports*'],
            ],
        ],

        'Pegawai Tenant' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'url' => '/dashboard',
                'active' => ['dashboard'],
            ],
            [
                'label' => 'Produk',
                'icon' => 'fa-solid fa-box',
                'url' => '#',
                'active' => ['products*'],
            ],
            [
                'label' => 'Transaksi',
                'icon' => 'fa-solid fa-receipt',
                'url' => '#',
                'active' => ['transactions*'],
            ],
        ],

        default => [],
    };
@endphp

<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-utensils"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">Kantin<span>.ONE</span></span>
            <small>Management System</small>
        </div>
    </div>
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <small>{{ ucwords(str_replace('_', ' ', $role)) }}</small>
        </div>
    </div>
    <div class="sidebar-menu-label">MENU UTAMA</div>
    <nav class="sidebar-nav">
        @foreach ($menus as $menu)
            <a href="{{ $menu['url'] }}" class="sidebar-link {{ request()->is(...$menu['active']) ? 'active' : '' }}" title="{{ $menu['label'] }}">
                <i class="{{ $menu['icon'] }}"></i>
                <span>{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="sidebar-bottom">
        <a href="#" class="sidebar-link" title="Pengaturan">
            <i class="fa-solid fa-gear"></i>
            <span>Pengaturan</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="sidebar-link sidebar-logout w-100">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
    <div class="sidebar-version">
        <span>Kantin.ONE v1.0</span>
    </div>
</aside>