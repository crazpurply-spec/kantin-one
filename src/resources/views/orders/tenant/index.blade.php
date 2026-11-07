@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="tenant-orders-page">

    <header class="tenant-orders-header">
        <div class="tenant-orders-brand">
            <span class="brand-icon">
                <i class="fa-solid fa-utensils"></i>
            </span>
            <div>
                <strong>Kantin<span>.ONE</span></strong>
                <small>Manajemen Tenant</small>
            </div>
        </div>

        <div class="tenant-profile">
            <div class="tenant-profile-info">
                <strong>Pegawai Tenant</strong>
                <small>Warung Nusantara</small>
            </div>
            <div class="tenant-profile-avatar">
                <i class="fa-solid fa-store"></i>
            </div>
        </div>
    </header>

    <main class="tenant-orders-container">

        <div class="tenant-page-heading">
            <div>
                <h1>Manajemen Pesanan</h1>
                <p>Kelola dan pantau pesanan pelanggan tenant kamu.</p>
            </div>

            <div class="tenant-heading-actions">
                <a href="{{ route('tenant.sales.index') }}" class="btn-refresh-orders">
                    <i class="fa-solid fa-chart-column"></i>
                    Verifikasi Penjualan
                </a>

                <button type="button" class="btn-refresh-orders" id="refreshOrders">
                    <i class="fa-solid fa-rotate"></i>
                    Refresh
                </button>
            </div>
        </div>

        <section class="order-statistics">
            <div class="order-stat-card">
                <div class="order-stat-icon waiting">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <span>Menunggu Konfirmasi</span>
                    <strong id="countWaiting">0</strong>
                </div>
            </div>

            <div class="order-stat-card">
                <div class="order-stat-icon processing">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
                <div>
                    <span>Sedang Diproses</span>
                    <strong id="countProcessing">0</strong>
                </div>
            </div>

            <div class="order-stat-card">
                <div class="order-stat-icon ready">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <span>Siap Diambil</span>
                    <strong id="countReady">0</strong>
                </div>
            </div>

            <div class="order-stat-card">
                <div class="order-stat-icon completed">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span>Selesai</span>
                    <strong id="countCompleted">0</strong>
                </div>
            </div>
        </section>

        <section class="orders-management-section">

            <div class="orders-toolbar">
                <div>
                    <h2>Daftar Pesanan</h2>
                    <p>Pesanan yang masuk ke Warung Nusantara.</p>
                </div>

                <div class="orders-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="search"
                        id="searchOrder"
                        placeholder="Cari nomor pesanan..."
                    >
                </div>
            </div>

            <div class="order-filter-tabs" id="orderFilters">
                <button class="active" data-filter="all">
                    Semua
                </button>
                <button data-filter="waiting_confirmation">
                    Menunggu Konfirmasi
                </button>
                <button data-filter="preparing">
                    Sedang Diproses
                </button>
                <button data-filter="ready">
                    Siap Diambil
                </button>
                <button data-filter="completed">
                    Selesai
                </button>
            </div>

            <div id="tenantOrderList" class="tenant-order-list"></div>

        </section>

    </main>
</div>

@vite('resources/js/pages/tenant-orders.js')
@endsection