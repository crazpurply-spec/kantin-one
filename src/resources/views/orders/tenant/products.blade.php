@extends('layouts.app')

@section('title', 'Ketersediaan Produk')

@section('content')
<div class="tenant-products-page">

    <div class="tenant-products-header">
        <div>
            <span class="page-eyebrow">Manajemen Produk</span>
            <h1>Ketersediaan Produk</h1>
            <p>Kelola status ketersediaan produk di tenant Anda.</p>
        </div>

        <div class="tenant-products-profile">
            <div class="profile-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <strong>Warung Nusantara</strong>
                <span>Pegawai Tenant</span>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="product-availability-stats">
        <div class="availability-stat-card">
            <div class="availability-stat-icon total">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <span>Total Produk</span>
                <h3 id="totalProducts">0</h3>
            </div>
        </div>

        <div class="availability-stat-card">
            <div class="availability-stat-icon available">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span>Produk Tersedia</span>
                <h3 id="availableProducts">0</h3>
            </div>
        </div>

        <div class="availability-stat-card">
            <div class="availability-stat-icon unavailable">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <span>Produk Tidak Tersedia</span>
                <h3 id="unavailableProducts">0</h3>
            </div>
        </div>
    </div>

    {{-- Daftar Produk --}}
    <section class="tenant-products-container">

        <div class="products-section-heading">
            <div>
                <h2>Daftar Produk</h2>
                <p>Aktifkan atau nonaktifkan produk sesuai ketersediaannya.</p>
            </div>
        </div>

        <div class="products-toolbar">

            <div class="products-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="search"
                    id="productSearch"
                    placeholder="Cari nama produk..."
                    autocomplete="off"
                >
            </div>

            <select id="categoryFilter" class="products-filter">
                <option value="all">Semua Kategori</option>
                <option value="makanan">Makanan</option>
                <option value="minuman">Minuman</option>
                <option value="snack">Snack</option>
            </select>

            <select id="availabilityFilter" class="products-filter">
                <option value="all">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="unavailable">Tidak Tersedia</option>
            </select>

        </div>

        <div class="products-table-wrapper">
            <table class="tenant-products-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status Ketersediaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="productsTableBody">
                    {{-- Diisi melalui JavaScript --}}
                </tbody>
            </table>
        </div>

        <div class="products-empty-state" id="productsEmptyState" hidden>
            <i class="fa-solid fa-box-open"></i>
            <h3>Produk Tidak Ditemukan</h3>
            <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>

        <div class="products-footer">
            <span id="productsResultCount">Menampilkan 0 produk</span>
        </div>

    </section>

</div>
@endsection

@push('styles')
    @vite('resources/css/pages/tenant-products.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/tenant-products.js')
@endpush