@extends('layouts.app')
@section('content')
<div class="dashboard-wrapper">
    @include('partials.sidebar')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <main class="dashboard-main">
        @include('partials.navbar')
        <div class="dashboard-content">
            <div class="card border-0 shadow mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    {{-- Page Information --}}
                    <div class="d-flex flex-column">
                        <h3 class="fw-bold">@yield('page-title')</h3>
                        <span class="text-muted">@yield('page-description')</span>
                        
                    </div>

                    {{-- Page Actions --}}
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @yield('page-actions')
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            @yield('button-list')

            <div class="flex-grow-1">
                @yield('dashboard-content')
            </div>
            @include('partials.footer')
        </div>
    </main>
</div>
@endsection
