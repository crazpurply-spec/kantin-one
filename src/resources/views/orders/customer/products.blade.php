@extends('layouts.app')

@section('title', 'Katalog Produk')

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

            <button type="button" class="cart-button" id="openCart">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>Keranjang</span>
                <span class="cart-count" id="cartCount">0</span>
            </button>
        </header>

        {{-- TENANT INFORMATION --}}
        <section class="product-tenant-heading">
            <a href="{{ route('customer.orders.tenants') }}" class="back-link">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Kembali ke Daftar Tenant
            </a>

            <div class="product-tenant-info">
                <div class="product-tenant-logo">
                    <i class="fa-solid fa-store"></i>
                </div>

                <div>
                    <span class="welcome-label">KATALOG TENANT</span>
                    <h1 id="tenantName">Warung Nusantara</h1>
                    <p id="tenantDescription">
                        Aneka makanan khas nusantara dengan cita rasa rumahan.
                    </p>

                    <span class="tenant-open">
                        <i class="fa-solid fa-circle"></i>
                        Buka
                    </span>
                </div>
            </div>
        </section>

        {{-- SEARCH --}}
        <div class="tenant-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search"
                   id="productSearch"
                   placeholder="Cari makanan atau minuman...">
        </div>

        {{-- CATEGORY --}}
        <div class="tenant-categories">
            <button class="category-btn active" data-category="all">
                Semua
            </button>
            <button class="category-btn" data-category="makanan">
                Makanan
            </button>
            <button class="category-btn" data-category="minuman">
                Minuman
            </button>
            <button class="category-btn" data-category="snack">
                Snack
            </button>
        </div>

        {{-- PRODUCT LIST --}}
        <section class="product-list-section">

            <div class="tenant-list-heading">
                <div>
                    <h4>Menu Produk</h4>
                    <p id="productCount">6 produk tersedia</p>
                </div>
            </div>

            <div class="product-grid" id="productGrid">

                {{-- PRODUCT 1 --}}
                <article class="product-card"
                    data-id="product-001"
                    data-name="Nasi Ayam Bakar"
                    data-category="makanan"
                    data-price="18000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Nasi Ayam Bakar">

                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Makanan</span>
                        <h5>Nasi Ayam Bakar</h5>
                        <p>Nasi putih dengan ayam bakar dan sambal.</p>

                        <div class="product-card-footer">
                            <strong>Rp18.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT 2 --}}
                <article class="product-card"
                    data-id="product-002"
                    data-name="Nasi Goreng Spesial"
                    data-category="makanan"
                    data-price="20000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Nasi Goreng Spesial">
                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Makanan</span>
                        <h5>Nasi Goreng Spesial</h5>
                        <p>Nasi goreng dengan telur dan topping pilihan.</p>

                        <div class="product-card-footer">
                            <strong>Rp20.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT 3 --}}
                <article class="product-card"
                    data-id="product-003"
                    data-name="Mie Goreng"
                    data-category="makanan"
                    data-price="15000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Mie Goreng">
                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Makanan</span>
                        <h5>Mie Goreng</h5>
                        <p>Mie goreng dengan sayuran dan telur.</p>

                        <div class="product-card-footer">
                            <strong>Rp15.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT 4 --}}
                <article class="product-card"
                    data-id="product-004"
                    data-name="Es Teh Manis"
                    data-category="minuman"
                    data-price="5000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Es Teh Manis">
                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Minuman</span>
                        <h5>Es Teh Manis</h5>
                        <p>Teh manis dingin yang menyegarkan.</p>

                        <div class="product-card-footer">
                            <strong>Rp5.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT 5 --}}
                <article class="product-card"
                    data-id="product-005"
                    data-name="Es Jeruk"
                    data-category="minuman"
                    data-price="8000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Es Jeruk">
                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Minuman</span>
                        <h5>Es Jeruk</h5>
                        <p>Minuman jeruk segar dengan es.</p>

                        <div class="product-card-footer">
                            <strong>Rp8.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT 6 --}}
                <article class="product-card"
                    data-id="product-006"
                    data-name="Pisang Goreng"
                    data-category="snack"
                    data-price="10000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Pisang Goreng">
                        <span class="product-available">Tersedia</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Snack</span>
                        <h5>Pisang Goreng</h5>
                        <p>Pisang goreng renyah dan hangat.</p>

                        <div class="product-card-footer">
                            <strong>Rp10.000</strong>
                            <button type="button" class="add-cart-btn">
                                <i class="fa-solid fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>

                {{-- PRODUCT UNAVAILABLE --}}
                <article class="product-card unavailable"
                    data-id="product-007"
                    data-name="Soto Ayam"
                    data-category="makanan"
                    data-price="17000">

                    <div class="product-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Soto Ayam">
                        <span class="product-unavailable">Habis</span>
                    </div>

                    <div class="product-card-body">
                        <span class="product-category">Makanan</span>
                        <h5>Soto Ayam</h5>
                        <p>Soto ayam dengan kuah gurih.</p>

                        <div class="product-card-footer">
                            <strong>Rp17.000</strong>
                            <button type="button" disabled>
                                Tidak Tersedia
                            </button>
                        </div>
                    </div>
                </article>

            </div>

            {{-- EMPTY STATE --}}
            <div class="tenant-empty d-none" id="productEmpty">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h5>Produk tidak ditemukan</h5>
                <p>Coba gunakan kata pencarian lainnya.</p>
            </div>

        </section>

        {{-- FLOATING CART --}}
        <button type="button" class="floating-cart d-none" id="floatingCart">
            <span>
                <i class="fa-solid fa-basket-shopping me-2"></i>
                Lihat Keranjang
            </span>
            <span id="floatingCartTotal">Rp0</span>
        </button>

    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/products-order.js')
@endpush