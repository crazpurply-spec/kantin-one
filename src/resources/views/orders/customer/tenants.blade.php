@extends('layouts.app')

@section('title', 'Pilih Tenant')

@section('content')
<div class="customer-order-page">

    <div class="order-container">

        {{-- HEADER --}}
        <header class="order-header">
            <a href="{{ route('customer.orders.index') }}" class="order-brand">
                <span class="brand-icon">
                    <i class="fa-solid fa-utensils"></i>
                </span>
                <span>Kantin<span class="text-primary">.ONE</span></span>
            </a>

            <button class="cart-button" type="button">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>Keranjang</span>
                <span class="cart-count">0</span>
            </button>
        </header>

        {{-- PAGE HEADING --}}
        <section class="tenant-heading">
            <span class="welcome-label">KANTIN.ONE ORDERING</span>
            <h1>Pilih <span>Tenant</span></h1>
            <p>
                Temukan makanan dan minuman favoritmu
                dari berbagai tenant kantin.
            </p>
        </section>

        {{-- SERVICE INFORMATION --}}
        <div class="selected-service">
            <div class="selected-service-icon">
                <i class="fa-solid fa-chair" id="serviceIcon"></i>
            </div>

            <div>
                <small>Jenis Layanan</small>
                <h6 id="serviceName">Dine In</h6>
            </div>

            <a href="{{ route('customer.orders.index') }}">
                Ubah
            </a>
        </div>

        {{-- SEARCH --}}
        <div class="tenant-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="search"
                id="tenantSearch"
                placeholder="Cari nama tenant..."
            >
        </div>

        {{-- CATEGORY --}}
        <div class="tenant-categories">
            <button class="category-btn active" data-category="all">
                Semua
            </button>

            <button class="category-btn" data-category="makanan">
                <i class="fa-solid fa-bowl-food me-1"></i>
                Makanan
            </button>

            <button class="category-btn" data-category="minuman">
                <i class="fa-solid fa-mug-hot me-1"></i>
                Minuman
            </button>

            <button class="category-btn" data-category="snack">
                <i class="fa-solid fa-cookie-bite me-1"></i>
                Snack
            </button>
        </div>

        {{-- TENANT LIST --}}
        <section class="tenant-list-section">

            <div class="tenant-list-heading">
                <div>
                    <h4>Tenant Kantin</h4>
                    <p id="tenantCount">4 tenant tersedia</p>
                </div>

                <span>
                    <i class="fa-solid fa-store me-1"></i>
                    Daftar Tenant
                </span>
            </div>

            <div class="tenant-grid" id="tenantGrid">

                {{-- TENANT 1 --}}
                <article class="tenant-card"
                    data-name="warung nusantara"
                    data-category="makanan">

                    <div class="tenant-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Warung Nusantara">

                        <span class="tenant-open">
                            <i class="fa-solid fa-circle"></i>
                            Buka
                        </span>
                    </div>

                    <div class="tenant-card-body">
                        <span class="tenant-category">Makanan</span>

                        <h5>Warung Nusantara</h5>

                        <p>
                            Aneka makanan khas nusantara
                            dengan cita rasa rumahan.
                        </p>

                        <div class="tenant-card-footer">
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                10–15 menit
                            </span>

                            <a href="{{ route('customer.orders.products', 'warung-nusantara') }}"
                                class="tenant-menu-btn">
                                    Lihat Menu
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </article>

                {{-- TENANT 2 --}}
                <article class="tenant-card"
                    data-name="kopi sudut"
                    data-category="minuman">

                    <div class="tenant-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Kopi Sudut">

                        <span class="tenant-open">
                            <i class="fa-solid fa-circle"></i>
                            Buka
                        </span>
                    </div>

                    <div class="tenant-card-body">
                        <span class="tenant-category">Minuman</span>

                        <h5>Kopi Sudut</h5>

                        <p>
                            Pilihan kopi, teh, dan minuman
                            segar untuk menemani aktivitas.
                        </p>

                        <div class="tenant-card-footer">
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                5–10 menit
                            </span>

                            <a href="{{ route('customer.orders.products', 'kopi-sudut') }}"
                                class="tenant-menu-btn">
                                    Lihat Menu
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </article>

                {{-- TENANT 3 --}}
                <article class="tenant-card"
                    data-name="snack corner"
                    data-category="snack">

                    <div class="tenant-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Snack Corner">

                        <span class="tenant-open">
                            <i class="fa-solid fa-circle"></i>
                            Buka
                        </span>
                    </div>

                    <div class="tenant-card-body">
                        <span class="tenant-category">Snack</span>

                        <h5>Snack Corner</h5>

                        <p>
                            Berbagai camilan dan makanan
                            ringan untuk menemani harimu.
                        </p>

                        <div class="tenant-card-footer">
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                5–10 menit
                            </span>

                            <a href="{{ route('customer.orders.products', 'snack-order') }}"
                                class="tenant-menu-btn">
                                    Lihat Menu
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </article>

                {{-- TENANT 4 --}}
                <article class="tenant-card"
                    data-name="dapur express"
                    data-category="makanan">

                    <div class="tenant-image">
                        <img src="{{ asset('img-dummy/supermarket.png') }}"
                             alt="Dapur Express">

                        <span class="tenant-closed">
                            <i class="fa-solid fa-circle"></i>
                            Tutup
                        </span>
                    </div>

                    <div class="tenant-card-body">
                        <span class="tenant-category">Makanan</span>

                        <h5>Dapur Express</h5>

                        <p>
                            Sajian makanan praktis untuk
                            kebutuhan sehari-hari.
                        </p>

                        <div class="tenant-card-footer">
                            <span>
                                <i class="fa-solid fa-clock"></i>
                                -
                            </span>

                            <a href="{{ route('customer.orders.products', 'dapur-express') }}"
                                class="tenant-menu-btn">
                                    Lihat Menu
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </article>

            </div>

            {{-- EMPTY STATE --}}
            <div class="tenant-empty d-none" id="tenantEmpty">
                <i class="fa-solid fa-store-slash"></i>
                <h5>Tenant tidak ditemukan</h5>
                <p>Coba gunakan kata pencarian lainnya.</p>
            </div>

        </section>

    </div>

</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/tenants-order.js')
@endpush