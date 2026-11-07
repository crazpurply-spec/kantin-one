@extends('layouts.app')

@section('title', 'Dashboard Pegawai Tenant')

@section('content')
<div class="tenant-dashboard">

    <header class="tenant-dashboard-header">
        <div>
            <span class="dashboard-greeting">Dashboard Pegawai Tenant</span>
            <h1>Selamat Datang!</h1>
            <p>Pantau aktivitas dan transaksi tenant Anda hari ini.</p>
        </div>

        <div class="tenant-profile">
            <div class="tenant-profile-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <strong>Warung Nusantara</strong>
                <span>Pegawai Tenant</span>
            </div>
        </div>
    </header>

    {{-- Statistik --}}
    <section class="tenant-statistics">
        <div class="tenant-stat-card stat-waiting">
            <div class="stat-card-icon">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div>
                <span>Menunggu Konfirmasi</span>
                <h2 id="waitingCount">0</h2>
                <small>Pesanan baru</small>
            </div>
        </div>

        <div class="tenant-stat-card stat-processing">
            <div class="stat-card-icon">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
            <div>
                <span>Sedang Diproses</span>
                <h2 id="processingCount">0</h2>
                <small>Pesanan dikerjakan</small>
            </div>
        </div>

        <div class="tenant-stat-card stat-ready">
            <div class="stat-card-icon">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div>
                <span>Siap Diambil</span>
                <h2 id="readyCount">0</h2>
                <small>Menunggu pelanggan</small>
            </div>
        </div>

        <div class="tenant-stat-card stat-completed">
            <div class="stat-card-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span>Pesanan Selesai</span>
                <h2 id="completedCount">0</h2>
                <small>Transaksi selesai</small>
            </div>
        </div>
    </section>

    {{-- Penjualan --}}
    <section class="tenant-sales-overview">
        <div class="sales-overview-content">
            <div class="sales-overview-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div>
                <span>Total Penjualan Hari Ini</span>
                <h2 id="todaySales">Rp0</h2>
                <p>Akumulasi pesanan selesai</p>
            </div>
        </div>

        <a href="{{ route('tenant.sales.index') }}" class="sales-overview-button">
            Lihat Penjualan
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </section>

    {{-- Menu Akses Cepat --}}
    <section class="tenant-quick-access">
        <div class="section-heading">
            <div>
                <h2>Akses Cepat</h2>
                <p>Pilih aktivitas yang ingin Anda lakukan.</p>
            </div>
        </div>

        <div class="quick-access-grid">
            <a href="{{ route('tenant.products.index') }}" class="quick-access-card">
                <div class="quick-access-icon access-products">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <h3>Ketersediaan Produk</h3>
                    <p>Aktifkan atau nonaktifkan produk tenant.</p>
                </div>
                <i class="fa-solid fa-arrow-right quick-access-arrow"></i>
            </a>
            <a href="{{ route('tenant.orders.index') }}" class="quick-access-card">
                <div class="quick-access-icon access-orders">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3>Manajemen Pesanan</h3>
                    <p>Lihat dan proses pesanan pelanggan.</p>
                </div>
                <i class="fa-solid fa-arrow-right quick-access-arrow"></i>
            </a>

            <a href="{{ route('tenant.sales.index') }}" class="quick-access-card">
                <div class="quick-access-icon access-sales">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
                <div>
                    <h3>Verifikasi Penjualan</h3>
                    <p>Periksa transaksi dan verifikasi penjualan.</p>
                </div>
                <i class="fa-solid fa-arrow-right quick-access-arrow"></i>
            </a>
        </div>
    </section>

    {{-- Pesanan Terbaru --}}
    <section class="tenant-recent-orders">
        <div class="section-heading">
            <div>
                <h2>Pesanan Terbaru</h2>
                <p>Ringkasan pesanan yang masuk ke tenant Anda.</p>
            </div>

            <a href="{{ route('tenant.orders.index') }}" class="view-all-orders">
                Lihat Semua
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="recent-orders-container" id="recentOrders">
            <div class="dashboard-empty-state">
                <i class="fa-solid fa-receipt"></i>
                <p>Belum ada pesanan masuk.</p>
            </div>
        </div>
    </section>

</div>
@endsection

@push('styles')
    @vite('resources/css/pages/tenant-dashboard.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/tenant-dashboard.js')
@endpush