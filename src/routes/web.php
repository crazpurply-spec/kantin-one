<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'super_admin', 'administrator' => redirect()->route('dashboard'),
        'canteen_manager' => redirect()->route('dashboard.canteen_manager'),
        'tenant', 'tenant_employee' => redirect()->route('dashboard.tenant'),
        default => abort(403),
    };
});

/* Order Page - Customer */
/* -------------------------------------------------------------------------- */
Route::get('/orders', function () {
    return view('orders.customer.index');
})->name('customer.orders.index');

Route::get('/pesan/tenant', function () {
    return view('orders.customer.tenants');
})->name('customer.orders.tenants');

Route::get('/pesan/tenant/{tenant}', function ($tenant) {
    return view('orders.customer.products', [
        'tenantSlug' => $tenant
    ]);
})->name('customer.orders.products');

Route::get('/pesan/keranjang', function () {
    return view('orders.customer.cart');
})->name('customer.orders.cart');

Route::get('/pesan/checkout', function () {
    return view('orders.customer.checkout');
})->name('customer.orders.checkout');

Route::get('/pesan/konfirmasi', function () {
    return view('orders.customer.confirmation');
})->name('customer.orders.confirmation');

Route::get('/pesan/status', function () {
    return view('orders.customer.status');
})->name('customer.orders.status');
/* -------------------------------------------------------------------------- */


Route::middleware(['auth', 'prevent.back', 'role:administrator'])->group(function () {

    /* Login Page */
    /* -------------------------------------------------------------------------- */
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    /* -------------------------------------------------------------------------- */

    /* User Management */
    /* -------------------------------------------------------------------------- */
    Route::post('/users/search', [UserController::class, 'index'])
        ->name('users.search');

    /* Index & Create */
    /* -------------------------------------------------------------------------- */
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');
    /* -------------------------------------------------------------------------- */

    /* Detail & Edit: pilih pengguna melalui POST */
    /* -------------------------------------------------------------------------- */
    Route::post('/users/detail', [UserController::class, 'selectDetail'])
        ->name('users.select-detail');

    Route::get('/users/detail', [UserController::class, 'show'])
        ->name('users.show');

    Route::post('/users/edit', [UserController::class, 'selectEdit'])
        ->name('users.select-edit');

    Route::get('/users/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('/users/edit', [UserController::class, 'update'])
        ->name('users.update');
    /* -------------------------------------------------------------------------- */

    /* Status & Delete */
    /* -------------------------------------------------------------------------- */
    Route::post('/users/status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    Route::post('/users/delete', [UserController::class, 'destroy'])
        ->name('users.destroy');
    /* -------------------------------------------------------------------------- */

    /* Trash, Restore & Permanent Delete */
    /* -------------------------------------------------------------------------- */
    Route::get('/users/trash', [UserController::class, 'trash'])
        ->name('users.trash');

    Route::post('/users/restore', [UserController::class, 'restore'])
        ->name('users.restore');

    Route::post('/users/forcedelete', [UserController::class, 'forceDelete'])
        ->name('users.force-delete');
    /* -------------------------------------------------------------------------- */

    /* Reset Password */
    /* -------------------------------------------------------------------------- */
    Route::post('/users/resetpassword/start', [UserController::class, 'startPasswordReset'])
        ->name('users.resetpassword.start');

    Route::get('/users/resetpassword', [UserController::class, 'editPassword'])
        ->name('users.resetpassword.edit');

    Route::patch('/users/resetpassword', [UserController::class, 'updatePassword'])
        ->name('users.resetpassword.update');
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    /* Tenant Management */
    /* -------------------------------------------------------------------------- */
    /* Index & Search */
    /* -------------------------------------------------------------------------- */
    Route::post('/tenants/search', [TenantController::class, 'index'])
        ->name('tenants.search');

    Route::get('/tenants', [TenantController::class, 'index'])
        ->name('tenants.index');
    /* -------------------------------------------------------------------------- */

    /* Create */
    /* -------------------------------------------------------------------------- */
    Route::get('/tenants/create', [TenantController::class, 'create'])
        ->name('tenants.create');

    Route::post('/tenants', [TenantController::class, 'store'])
        ->name('tenants.store');
    /* -------------------------------------------------------------------------- */

    /* Detail */
    /* -------------------------------------------------------------------------- */
    Route::post('/tenants/detail', [TenantController::class, 'selectDetail'])
        ->name('tenants.select-detail');

    Route::get('/tenants/detail', [TenantController::class, 'show'])
        ->name('tenants.show');
    /* -------------------------------------------------------------------------- */

    /* Edit */
    /* -------------------------------------------------------------------------- */
    Route::post('/tenants/edit', [TenantController::class, 'selectEdit'])
        ->name('tenants.select-edit');

    Route::get('/tenants/edit', [TenantController::class, 'edit'])
        ->name('tenants.edit');

    Route::put('/tenants/edit', [TenantController::class, 'update'])
        ->name('tenants.update');
    /* -------------------------------------------------------------------------- */

    /* Status & Delete */
    /* -------------------------------------------------------------------------- */
    Route::post('/tenants/status', [TenantController::class, 'toggleStatus'])
        ->name('tenants.toggle-status');

    Route::post('/tenants/delete', [TenantController::class, 'destroy'])
        ->name('tenants.destroy');
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    /* Product Management */
    /* -------------------------------------------------------------------------- */

    /* Index & Search */
    Route::post('/products/search', [ProductController::class, 'index'])
        ->name('products.search');

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    /* Create */
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    /* Detail */
    Route::post('/products/detail', [ProductController::class, 'selectDetail'])
        ->name('products.select-detail');

    Route::get('/products/detail', [ProductController::class, 'show'])
        ->name('products.show');

    /* Edit */
    Route::post('/products/edit', [ProductController::class, 'selectEdit'])
        ->name('products.select-edit');

    Route::get('/products/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/edit', [ProductController::class, 'update'])
        ->name('products.update');

    /* Availability & Delete */
    Route::post('/products/status', [ProductController::class, 'toggleStatus'])
        ->name('products.toggle-status');

    Route::post('/products/delete', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::get('/products/trash', [ProductController::class, 'trash'])
        ->name('products.trash');

    Route::post('/products/restore', [ProductController::class, 'restore'])
        ->name('products.restore');

    Route::post('/products/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.force-delete');

    /* -------------------------------------------------------------------------- */

});

/* Dashboard - Kepala Pengelola Tenant */
/* -------------------------------------------------------------------------- */
Route::middleware(['auth', 'prevent.back', 'role:canteen_manager'])->group(function () {
    Route::get('/pengelola-tenant/dashboard', function () {
        return view('dashboard.canteen_manager.index');
    })->name('dashboard.canteen_manager');

    Route::get('/tenant/pesanan', function () {
        return view('orders.tenant.index');
    })->name('tenant.orders.index');

    Route::get('/tenant/penjualan', function () {
        return view('orders.tenant.sales');
    })->name('tenant.sales.index');

    Route::get('/tenant/dashboard', function () {
        return view('orders.tenant.dashboard');
    })->name('tenant.dashboard');

    Route::get('/tenant/produk', function () {
        return view('orders.tenant.products');
    })->name('tenant.products.index');
    });
/* -------------------------------------------------------------------------- */

/* Dashboard - Kepala Tenant & Pegawai Tenant */
/* -------------------------------------------------------------------------- */
Route::middleware(['auth', 'prevent.back', 'role:tenant,tenant_employee'])->group(function () {
    Route::get('/tenant/dashboard', function () {
        return view('dashboard.tenant.index');
    })->name('dashboard.tenant');
});
