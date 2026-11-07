@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="customer-order-page">
    <div class="order-container">

        {{-- HEADER --}}
        <header class="order-header">
            <a href="{{ route('customer.orders.tenants') }}" class="order-brand">
                <span class="brand-icon">
                    <i class="fa-solid fa-utensils"></i>
                </span>
                <span>Kantin<span class="text-primary">.ONE</span></span>
            </a>

            <span class="cart-header-label">
                <i class="fa-solid fa-lock me-2"></i>
                Checkout Aman
            </span>
        </header>

        {{-- HEADING --}}
        <section class="checkout-heading">
            <a href="{{ route('customer.orders.cart') }}" class="back-link">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali ke Keranjang
            </a>

            <h1>Checkout <span>Pesanan</span></h1>
            <p>Periksa detail pesanan dan pilih metode pembayaran.</p>
        </section>

        <form id="checkoutForm">

            <div class="checkout-layout">

                <div class="checkout-main">

                    {{-- SERVICE INFORMATION --}}
                    <section class="checkout-card">

                        <div class="checkout-card-heading">
                            <div class="checkout-step">01</div>
                            <div>
                                <h4>Informasi Pengambilan</h4>
                                <p>Periksa jenis layanan pesananmu.</p>
                            </div>
                        </div>

                        <div class="checkout-service-info">
                            <div class="checkout-service-icon">
                                <i class="fa-solid fa-chair" id="checkoutServiceIcon"></i>
                            </div>

                            <div>
                                <small>Jenis Layanan</small>
                                <h5 id="checkoutServiceName">Dine In</h5>
                                <p id="checkoutServiceDescription">
                                    Pesanan dinikmati di area kantin.
                                </p>
                            </div>
                        </div>

                        {{-- TABLE INFORMATION --}}
                        <div class="checkout-detail-row" id="tableInformation">
                            <span>
                                <i class="fa-solid fa-location-dot me-2"></i>
                                Lokasi Meja
                            </span>
                            <strong id="checkoutTable">Area Utama · Meja A-012</strong>
                        </div>

                        {{-- PICKUP SCHEDULE --}}
                        <div id="pickupInformation" class="d-none">

                            <div class="checkout-input-group">
                                <label for="checkoutPickupDate">
                                    Tanggal Pengambilan
                                </label>

                                <input type="date"
                                       class="form-control"
                                       id="checkoutPickupDate">
                            </div>

                            <div class="checkout-input-group">
                                <label for="checkoutPickupTime">
                                    Waktu Pengambilan
                                </label>

                                <input type="time"
                                       class="form-control"
                                       id="checkoutPickupTime">
                            </div>

                        </div>

                    </section>

                    {{-- ORDER SUMMARY --}}
                    <section class="checkout-card">

                        <div class="checkout-card-heading">
                            <div class="checkout-step">02</div>
                            <div>
                                <h4>Detail Pesanan</h4>
                                <p>Daftar produk dari tenant pilihanmu.</p>
                            </div>
                        </div>

                        <div id="checkoutOrderItems"></div>

                    </section>

                    {{-- ORDER NOTES --}}
                    <section class="checkout-card">

                        <div class="checkout-card-heading">
                            <div class="checkout-step">03</div>
                            <div>
                                <h4>Catatan Pesanan</h4>
                                <p>Tambahkan instruksi khusus jika diperlukan.</p>
                            </div>
                        </div>

                        <textarea
                            class="form-control checkout-notes"
                            id="orderNotes"
                            rows="3"
                            maxlength="500"
                            placeholder="Contoh: Tidak pedas, saus dipisah, dan sebagainya."
                        ></textarea>

                        <small class="notes-information">
                            Catatan ini berlaku untuk seluruh pesanan.
                        </small>

                    </section>

                    {{-- PAYMENT METHOD --}}
                    <section class="checkout-card">

                        <div class="checkout-card-heading">
                            <div class="checkout-step">04</div>
                            <div>
                                <h4>Metode Pembayaran</h4>
                                <p>Pilih satu metode pembayaran untuk seluruh pesanan.</p>
                            </div>
                        </div>

                        <div class="payment-methods">

                            <label class="payment-option">
                                <input type="radio"
                                       name="payment_method"
                                       value="qris"
                                       required>

                                <span class="payment-icon qris-icon">
                                    <i class="fa-solid fa-qrcode"></i>
                                </span>

                                <span class="payment-info">
                                    <strong>QRIS</strong>
                                    <small>Bayar menggunakan aplikasi pembayaran digital.</small>
                                </span>

                                <i class="fa-solid fa-circle-check payment-check"></i>
                            </label>

                            <label class="payment-option">
                                <input type="radio"
                                       name="payment_method"
                                       value="bank_transfer">

                                <span class="payment-icon transfer-icon">
                                    <i class="fa-solid fa-building-columns"></i>
                                </span>

                                <span class="payment-info">
                                    <strong>Transfer Bank</strong>
                                    <small>Transfer melalui rekening bank.</small>
                                </span>

                                <i class="fa-solid fa-circle-check payment-check"></i>
                            </label>

                            <label class="payment-option">
                                <input type="radio"
                                       name="payment_method"
                                       value="cash">

                                <span class="payment-icon cash-icon">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </span>

                                <span class="payment-info">
                                    <strong>Tunai</strong>
                                    <small>Bayar langsung sesuai prosedur kantin.</small>
                                </span>

                                <i class="fa-solid fa-circle-check payment-check"></i>
                            </label>

                        </div>

                    </section>

                </div>

                {{-- PAYMENT SUMMARY --}}
                <aside class="checkout-summary">

                    <h4>Ringkasan Pembayaran</h4>

                    <div class="summary-row">
                        <span>Total Tenant</span>
                        <strong id="checkoutTenantCount">0</strong>
                    </div>

                    <div class="summary-row">
                        <span>Total Produk</span>
                        <strong id="checkoutProductCount">0</strong>
                    </div>

                    <hr>

                    <div id="checkoutTenantSubtotals"></div>

                    <hr>

                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <strong id="checkoutGrandTotal">Rp0</strong>
                    </div>

                    <button type="submit"
                            class="checkout-btn"
                            id="placeOrderButton">
                        Buat Pesanan
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <div class="summary-information">
                        <i class="fa-solid fa-shield-halved"></i>
                        Pastikan seluruh informasi pesanan sudah benar.
                    </div>

                </aside>

            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/checkout-order.js')
@endpush