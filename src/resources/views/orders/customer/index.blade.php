@extends('layouts.app')

@section('title', 'Pemesanan')

@section('content')
<div class="customer-order-page">

    <div class="order-container">

        {{-- HEADER --}}
        <header class="order-header">
            <a href="#" class="order-brand">
                <span class="brand-icon">
                    <i class="fa-solid fa-utensils"></i>
                </span>
                <span>Kantin<span class="text-primary">.ONE</span></span>
            </a>

            <span class="order-badge">
                <i class="fa-solid fa-circle-check"></i>
                Pemesanan Online
            </span>
        </header>

        {{-- WELCOME --}}
        <section class="order-welcome">
            <span class="welcome-label">SELAMAT DATANG</span>

            <h1>
                Mau makan apa<br>
                <span>hari ini?</span>
            </h1>

            <p>
                Nikmati kemudahan memesan makanan dan minuman
                dari berbagai tenant kantin.
            </p>
        </section>

        {{-- TABLE INFORMATION --}}
        <div class="table-information">
            <div class="table-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>

            <div>
                <small>Lokasi Pemesanan</small>
                <h6>Area Utama · Meja A-012</h6>
            </div>

            <span class="table-status">
                <i class="fa-solid fa-circle"></i>
                Terhubung
            </span>
        </div>

        {{-- SERVICE SELECTION --}}
        <section class="service-section">

            <div class="section-heading">
                <div>
                    <h4>Pilih Jenis Layanan</h4>
                    <p>Bagaimana Anda ingin menikmati pesanan?</p>
                </div>
            </div>

            <div class="service-list">

                {{-- DINE IN --}}
                <label class="service-card">
                    <input
                        type="radio"
                        name="service_type"
                        value="dine_in"
                    >

                    <div class="service-icon dine-in">
                        <i class="fa-solid fa-chair"></i>
                    </div>

                    <div class="service-information">
                        <h5>Dine In</h5>
                        <p>
                            Nikmati makanan dan minuman
                            langsung di area kantin.
                        </p>
                    </div>

                    <div class="service-check">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </label>

                {{-- TAKE AWAY --}}
                <label class="service-card">
                    <input
                        type="radio"
                        name="service_type"
                        value="take_away"
                    >

                    <div class="service-icon take-away">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>

                    <div class="service-information">
                        <h5>Take Away</h5>
                        <p>
                            Pesan sekarang dan bawa
                            pulang pesanan Anda.
                        </p>
                    </div>

                    <div class="service-check">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </label>

                {{-- AFTER EVENT --}}
                <label class="service-card">
                    <input
                        type="radio"
                        name="service_type"
                        value="after_event"
                    >

                    <div class="service-icon after-event">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <div class="service-information">
                        <h5>Diambil Setelah Kegiatan</h5>
                        <p>
                            Pesan sekarang dan tentukan
                            waktu pengambilan nanti.
                        </p>
                    </div>

                    <div class="service-check">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </label>

            </div>

            {{-- SCHEDULE --}}
            <div class="schedule-section d-none" id="scheduleSection">

                <h6>
                    <i class="fa-regular fa-clock me-2"></i>
                    Jadwal Pengambilan
                </h6>

                <div class="row g-3">

                    <div class="col-6">
                        <label for="pickupDate" class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="pickupDate"
                        >
                    </div>

                    <div class="col-6">
                        <label for="pickupTime" class="form-label">
                            Jam
                        </label>

                        <input
                            type="time"
                            class="form-control"
                            id="pickupTime"
                        >
                    </div>

                </div>
            </div>

            {{-- CONTINUE --}}
            <button
                type="button"
                class="btn btn-continue"
                id="continueOrder"
                disabled
            >
                Lanjutkan
                <i class="fa-solid fa-arrow-right"></i>
            </button>

            <div class="order-footer">
                <i class="fa-solid fa-shield-halved"></i>
                Pemesanan mudah, aman, dan praktis.
            </div>

        </section>

    </div>

</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/customer-order.js')
@endpush