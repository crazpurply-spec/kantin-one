@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="confirmation-page">

    <header class="confirmation-header">
        <a href="{{ route('customer.orders.index') }}" class="confirmation-brand">
            <span class="brand-icon">
                <i class="fa-solid fa-utensils"></i>
            </span>
            <span>Kantin<span class="brand-highlight">.ONE</span></span>
        </a>

        <div class="confirmation-header-label">
            <i class="fa-solid fa-receipt"></i>
            Konfirmasi Pesanan
        </div>
    </header>

    <main class="confirmation-container">

        <section class="confirmation-success">
            <div class="success-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1>Pesanan Berhasil Dibuat!</h1>
            <p>
                Pesanan kamu sudah kami terima. Silakan perhatikan
                informasi pembayaran dan kode pengambilan berikut.
            </p>

            <div class="parent-order-number">
                <span>Nomor Pesanan Utama</span>
                <strong id="parentOrderNumber">-</strong>
            </div>
        </section>

        <section class="payment-status-card">
            <div class="payment-status-icon">
                <i class="fa-solid fa-wallet"></i>
            </div>

            <div class="payment-status-content">
                <span>Status Pembayaran</span>
                <strong id="paymentStatus">Menunggu Pembayaran</strong>
                <small id="paymentDescription">
                    Silakan selesaikan pembayaran sesuai metode yang dipilih.
                </small>
            </div>

            <span class="payment-status-badge" id="paymentBadge">
                Belum Dibayar
            </span>
        </section>

        <section class="confirmation-section">
            <div class="section-heading">
                <div>
                    <h2>Informasi Pembayaran</h2>
                    <p>Metode pembayaran pesanan kamu.</p>
                </div>
                <i class="fa-solid fa-credit-card"></i>
            </div>

            <div class="payment-information">
                <div class="payment-info-row">
                    <span>Metode Pembayaran</span>
                    <strong id="paymentMethod">-</strong>
                </div>

                <div class="payment-info-row">
                    <span>Jenis Layanan</span>
                    <strong id="serviceType">-</strong>
                </div>

                <div class="payment-info-row" id="pickupScheduleRow" hidden>
                    <span>Jadwal Pengambilan</span>
                    <strong id="pickupSchedule">-</strong>
                </div>

                <div class="payment-info-row">
                    <span>Jumlah Tenant</span>
                    <strong id="tenantCount">0 Tenant</strong>
                </div>

                <div class="payment-info-row payment-total-row">
                    <span>Total Pembayaran</span>
                    <strong id="grandTotal">Rp0</strong>
                </div>
            </div>

            <div class="payment-instruction" id="paymentInstruction">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    <strong>Informasi Pembayaran</strong>
                    <p id="paymentInstructionText">
                        Informasi pembayaran akan ditampilkan di sini.
                    </p>
                </div>
            </div>
        </section>

        <section class="confirmation-section">
            <div class="section-heading">
                <div>
                    <h2>Kode Pengambilan</h2>
                    <p>
                        Setiap tenant memiliki nomor pesanan dan kode
                        pengambilan masing-masing.
                    </p>
                </div>
                <i class="fa-solid fa-ticket"></i>
            </div>

            <div id="tenantOrders" class="tenant-orders-list"></div>
        </section>

        <section class="confirmation-note">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Perhatikan informasi berikut</strong>
                <p>
                    Simpan kode pengambilan masing-masing tenant.
                    Tunjukkan kode tersebut kepada pegawai tenant
                    ketika mengambil pesanan.
                </p>
            </div>
        </section>

        <div class="confirmation-actions">
            <a href="{{ route('customer.orders.status') }}" class="btn-back-home">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Lihat Status Pesanan
            </a>

            <a href="{{ route('customer.orders.index') }}" class="btn-back-home">
                <i class="fa-solid fa-house"></i>
                Kembali ke Beranda
            </a>
        </div>

    </main>
</div>

@vite('resources/js/pages/confirmation-order.js')
@endsection