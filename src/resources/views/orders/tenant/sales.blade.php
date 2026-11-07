@extends('layouts.app')

@section('title', 'Verifikasi Penjualan')

@section('content')
<div class="tenant-sales-page">

    <header class="tenant-sales-header">
        <div class="tenant-sales-brand">
            <span class="brand-icon">
                <i class="fa-solid fa-utensils"></i>
            </span>
            <div>
                <strong>Kantin<span>.ONE</span></strong>
                <small>Manajemen Tenant</small>
            </div>
        </div>

        <div class="tenant-sales-profile">
            <div>
                <strong>Pegawai Tenant</strong>
                <small>Warung Nusantara</small>
            </div>
            <span class="profile-avatar">
                <i class="fa-solid fa-store"></i>
            </span>
        </div>
    </header>

    <main class="tenant-sales-container">

        <div class="sales-page-heading">
            <div>
                <h1>Verifikasi Penjualan</h1>
                <p>Periksa dan verifikasi total penjualan tenant.</p>
            </div>

            <a href="{{ route('tenant.orders.index') }}" class="btn-back-orders">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Pesanan
            </a>
        </div>

        <section class="sales-date-card">
            <div>
                <i class="fa-solid fa-calendar-day"></i>
                <span>Periode Penjualan</span>
                <strong id="salesDate">-</strong>
            </div>

            <span class="sales-period-badge">Harian</span>
        </section>

        <section class="sales-statistics">

            <div class="sales-stat-card">
                <div class="sales-stat-icon revenue">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <span>Total Penjualan</span>
                    <strong id="totalSales">Rp0</strong>
                </div>
            </div>

            <div class="sales-stat-card">
                <div class="sales-stat-icon transactions">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <span>Transaksi Selesai</span>
                    <strong id="completedOrders">0</strong>
                </div>
            </div>

            <div class="sales-stat-card">
                <div class="sales-stat-icon pending">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <span>Pesanan Belum Selesai</span>
                    <strong id="pendingOrders">0</strong>
                </div>
            </div>

            <div class="sales-stat-card">
                <div class="sales-stat-icon average">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <span>Rata-rata Transaksi</span>
                    <strong id="averageSales">Rp0</strong>
                </div>
            </div>

        </section>

        <section class="sales-transactions-section">

            <div class="sales-section-heading">
                <div>
                    <h2>Daftar Transaksi</h2>
                    <p>Periksa transaksi yang telah diselesaikan.</p>
                </div>

                <div class="sales-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="search"
                        id="searchSales"
                        placeholder="Cari nomor pesanan..."
                    >
                </div>
            </div>

            <div class="sales-filter-tabs" id="salesFilters">
                <button class="active" data-filter="all">Semua</button>
                <button data-filter="completed">Selesai</button>
                <button data-filter="pending">Belum Selesai</button>
            </div>

            <div class="sales-table-wrapper">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Waktu</th>
                            <th>Jumlah Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody"></tbody>
                </table>
            </div>

            <div class="sales-mobile-list" id="salesMobileList"></div>

        </section>

        <section class="sales-verification-card">

            <div class="verification-heading">
                <div class="verification-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>

                <div>
                    <h2>Verifikasi Penjualan</h2>
                    <p>
                        Pastikan seluruh transaksi dan total penjualan
                        telah diperiksa sebelum melakukan closing.
                    </p>
                </div>
            </div>

            <div class="verification-summary">
                <div>
                    <span>Total Transaksi Selesai</span>
                    <strong id="verificationCount">0 Transaksi</strong>
                </div>

                <div>
                    <span>Total Penjualan Terverifikasi</span>
                    <strong id="verificationTotal">Rp0</strong>
                </div>
            </div>

            <div class="verification-warning" id="verificationWarning">
                <i class="fa-solid fa-circle-info"></i>
                <span>
                    Pastikan seluruh pesanan sudah diselesaikan
                    sebelum melakukan penutupan penjualan.
                </span>
            </div>

            <label class="verification-checkbox">
                <input type="checkbox" id="confirmVerification">

                <span>
                    Saya telah memeriksa dan memastikan bahwa total
                    penjualan tenant sudah sesuai.
                </span>
            </label>

            <button
                type="button"
                class="btn-close-sales"
                id="closeSales"
                disabled
            >
                <i class="fa-solid fa-lock"></i>
                Verifikasi & Tutup Penjualan
            </button>

        </section>

    </main>
</div>

@vite('resources/js/pages/tenant-sales.js')
@endsection