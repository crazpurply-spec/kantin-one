@extends('layouts.app')

@section('title', 'Status Pesanan')

@section('content')
<div class="order-status-page">

    <header class="order-status-header">
        <a href="{{ route('customer.orders.index') }}" class="status-brand">
            <span class="brand-icon">
                <i class="fa-solid fa-utensils"></i>
            </span>
            <span>Kantin<span class="brand-highlight">.ONE</span></span>
        </a>

        <span class="header-label">
            <i class="fa-solid fa-location-dot"></i>
            Lacak Pesanan
        </span>
    </header>

    <main class="order-status-container">

        <div class="status-page-heading">
            <div>
                <h1>Status Pesanan</h1>
                <p>Pantau perkembangan pesanan kamu dari setiap tenant.</p>
            </div>

            <button type="button" class="btn-refresh-status" id="refreshStatus">
                <i class="fa-solid fa-rotate"></i>
                Refresh
            </button>
        </div>

        <section class="main-order-card">
            <div class="main-order-top">
                <div>
                    <span class="main-order-label">Nomor Pesanan Utama</span>
                    <strong id="parentOrderNumber">-</strong>
                </div>

                <span class="main-order-badge" id="mainOrderStatus">
                    Menunggu Pembayaran
                </span>
            </div>

            <div class="main-order-information">
                <div>
                    <i class="fa-solid fa-wallet"></i>
                    <span>Metode Pembayaran</span>
                    <strong id="paymentMethod">-</strong>
                </div>

                <div>
                    <i class="fa-solid fa-utensils"></i>
                    <span>Jenis Layanan</span>
                    <strong id="serviceType">-</strong>
                </div>

                <div id="pickupScheduleContainer" hidden>
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Pengambilan</span>
                    <strong id="pickupSchedule">-</strong>
                </div>

                <div>
                    <i class="fa-solid fa-store"></i>
                    <span>Jumlah Tenant</span>
                    <strong id="tenantCount">0 Tenant</strong>
                </div>
            </div>

            <div class="main-order-total">
                <span>Total Pesanan</span>
                <strong id="grandTotal">Rp0</strong>
            </div>
        </section>

        <section class="tenant-tracking-section">
            <div class="tracking-section-heading">
                <div>
                    <h2>Perkembangan Pesanan</h2>
                    <p>Setiap tenant memiliki proses pesanan masing-masing.</p>
                </div>
                <i class="fa-solid fa-list-check"></i>
            </div>

            <div id="tenantTrackingList" class="tenant-tracking-list"></div>
        </section>

        <div class="status-page-actions">
            <a href="{{ route('customer.orders.index') }}" class="btn-status-home">
                <i class="fa-solid fa-house"></i>
                Kembali ke Beranda
            </a>
        </div>

    </main>
</div>

@vite('resources/js/pages/status-order.js')
@endsection