@extends('layouts.app')

@section('title', 'Keranjang Belanja')

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
                <i class="fa-solid fa-basket-shopping me-2"></i>
                Keranjang Belanja
            </span>
        </header>

        {{-- HEADING --}}
        <section class="cart-heading">
            <a href="{{ route('customer.orders.tenants') }}" class="back-link">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali Belanja
            </a>

            <h1>Keranjang <span>Belanja</span></h1>
            <p>Periksa kembali pesananmu sebelum melanjutkan.</p>
        </section>

        <div class="cart-layout">

            {{-- CART ITEMS --}}
            <section class="cart-main">

                <div class="cart-section-heading">
                    <div>
                        <h4>Produk Pilihan</h4>
                        <p id="cartItemCount">0 produk</p>
                    </div>
                </div>

                <div id="cartTenantGroups"></div>

                {{-- EMPTY CART --}}
                <div class="cart-empty d-none" id="cartEmpty">
                    <div class="cart-empty-icon">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>

                    <h4>Keranjang Masih Kosong</h4>
                    <p>
                        Kamu belum menambahkan produk apa pun.
                        Yuk, temukan makanan favoritmu!
                    </p>

                    <a href="{{ route('customer.orders.tenants') }}"
                       class="cart-shopping-btn">
                        <i class="fa-solid fa-store me-2"></i>
                        Mulai Belanja
                    </a>
                </div>

            </section>

            {{-- ORDER SUMMARY --}}
            <aside class="cart-summary" id="cartSummary">

                <h4>Ringkasan Pesanan</h4>

                <div class="summary-row">
                    <span>Total Produk</span>
                    <strong id="summaryQuantity">0</strong>
                </div>

                <div class="summary-row">
                    <span>Jumlah Tenant</span>
                    <strong id="summaryTenantCount">0</strong>
                </div>

                <hr>

                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <strong id="summaryTotal">Rp0</strong>
                </div>

                <button type="button"
                        class="checkout-btn"
                        id="checkoutButton">
                    Lanjut Checkout
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <div class="summary-information">
                    <i class="fa-solid fa-circle-info"></i>
                    Pembayaran dilakukan dalam satu transaksi
                    untuk seluruh tenant pilihanmu.
                </div>

            </aside>

        </div>

    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/cart-order.js')
@endpush