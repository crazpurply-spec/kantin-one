@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0">
        @include('partials.tenant_dashboard.navbar')
        <div class="container">
            @yield('dashboard-content')
        </div>
    </div>
    {{-- Quick Actions --}}
    @yield('button-list')
    @yield('page-actions')
    @yield('page-title')
    @yield('page-description')
@endsection
