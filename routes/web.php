<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Admin;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

// ─── Welcome ────────────────────────────────────────────────────────────────
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // Google OAuth
    Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Super Admin ─────────────────────────────────────────────────────────────
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {

        Route::get('/dashboard', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Users management
        Route::get('/users',              [SuperAdmin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',       [SuperAdmin\UserController::class, 'create'])->name('users.create');
        Route::post('/users',             [SuperAdmin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',  [SuperAdmin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',       [SuperAdmin\UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle', [SuperAdmin\UserController::class, 'toggleActive'])->name('users.toggle');

        // Top-up management
        Route::get('/topup',                          [SuperAdmin\TopupController::class, 'index'])->name('topup.index');
        Route::get('/topup/{topupRequest}',           [SuperAdmin\TopupController::class, 'show'])->name('topup.show');
        Route::patch('/topup/{topupRequest}/approve', [SuperAdmin\TopupController::class, 'approve'])->name('topup.approve');
        Route::patch('/topup/{topupRequest}/reject',  [SuperAdmin\TopupController::class, 'reject'])->name('topup.reject');

        // Withdrawal management
        Route::get('/withdrawal',                                  [SuperAdmin\WithdrawalController::class, 'index'])->name('withdrawal.index');
        Route::get('/withdrawal/{withdrawalRequest}',              [SuperAdmin\WithdrawalController::class, 'show'])->name('withdrawal.show');
        Route::patch('/withdrawal/{withdrawalRequest}/approve',    [SuperAdmin\WithdrawalController::class, 'approve'])->name('withdrawal.approve');
        Route::post('/withdrawal/{withdrawalRequest}/transfer',    [SuperAdmin\WithdrawalController::class, 'transfer'])->name('withdrawal.transfer');
        Route::patch('/withdrawal/{withdrawalRequest}/reject',     [SuperAdmin\WithdrawalController::class, 'reject'])->name('withdrawal.reject');
    });

// ─── Admin (Seller) ───────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Menu CRUD
        Route::get('/menu',                     [Admin\MenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create',              [Admin\MenuController::class, 'create'])->name('menu.create');
        Route::post('/menu',                    [Admin\MenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{menu}/edit',         [Admin\MenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{menu}',              [Admin\MenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menu}',           [Admin\MenuController::class, 'destroy'])->name('menu.destroy');
        Route::patch('/menu/{menu}/status',     [Admin\MenuController::class, 'toggleStatus'])->name('menu.toggle');

        // Orders / Antrian
        Route::get('/orders',                   [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',           [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status',  [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

        // Laporan
        Route::get('/laporan', [Admin\OrderController::class, 'laporan'])->name('laporan');

        // Pencairan Dana
        Route::get('/withdrawal',    [Admin\WithdrawalController::class, 'index'])->name('withdrawal.index');
        Route::post('/withdrawal',   [Admin\WithdrawalController::class, 'store'])->name('withdrawal.store');
    });

// ─── User (Siswa/Guru) ────────────────────────────────────────────────────────
Route::prefix('pesanan')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');

        // Pre-Order
        Route::get('/katalog',              [User\OrderController::class, 'catalog'])->name('catalog');
        Route::post('/checkout',            [User\OrderController::class, 'checkout'])->name('checkout');
        Route::post('/order',               [User\OrderController::class, 'store'])->name('order.store');
        Route::get('/riwayat',              [User\OrderController::class, 'index'])->name('orders');
        Route::get('/riwayat/{order}',      [User\OrderController::class, 'show'])->name('order.show');
        Route::patch('/riwayat/{order}/cancel', [User\OrderController::class, 'cancel'])->name('order.cancel');

        // Saldo
        Route::get('/saldo',        [User\SaldoController::class, 'index'])->name('saldo');
        Route::get('/saldo/topup',  [User\SaldoController::class, 'topupForm'])->name('saldo.topup');
        Route::post('/saldo/topup', [User\SaldoController::class, 'topupStore'])->name('saldo.topup.store');
    });
