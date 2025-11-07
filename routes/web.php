<?php

use App\Http\Controllers\Admin\ApprovalsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstallmentsController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\MembersController;
use App\Http\Controllers\Admin\ProductCategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\SavingsController;
use App\Http\Controllers\Admin\ShuController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\CartController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\FinancesController;
use App\Http\Controllers\Member\StatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SystemConfigurationController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'verified', 'role:Super Admin'])
    ->group(function () {
        Route::get('/dashboard', SuperAdminDashboardController::class)->name('dashboard');
        Route::get('/system-configuration', SystemConfigurationController::class)->name('system-configuration.index');
        Route::resource('admins', \App\Http\Controllers\SuperAdmin\AdminController::class);
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:Admin,Super Admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/members', [MembersController::class, 'index'])->name('members.index');
        Route::get('/product-categories', ProductCategoriesController::class)->name('product-categories.index');
        Route::resource('/products', ProductsController::class);
        Route::get('/transactions', TransactionsController::class)->name('transactions.index');
        Route::get('/installments', InstallmentsController::class)->name('installments.index');
        Route::get('/savings', SavingsController::class)->name('savings.index');
        Route::get('/shu', ShuController::class)->name('shu.index');
        Route::get('/approvals', [ApprovalsController::class, 'index'])->name('approvals');
        Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger');
    });

Route::prefix('member')
    ->name('member.')
    ->middleware(['auth', 'verified', 'role:Anggota'])
    ->group(function () {
    Route::get('/dashboard', MemberDashboardController::class)->name('dashboard');
    Route::get('/cart', CartController::class)->name('cart');
        Route::get('/finances', [FinancesController::class, 'index'])->name('finances');
        Route::get('/finances/add/{type}', [FinancesController::class, 'create'])->name('finances.create');
        Route::post('/finances/store', [FinancesController::class, 'store'])->name('finances.store');
    Route::get('/status', StatusController::class)->name('status');
    Route::get('/status/{id}', [StatusController::class, 'show'])->name('status.show');
    Route::get('/products', [\App\Http\Controllers\Member\ProductsController::class, 'index'])->name('products');
        Route::get('/products/{product:slug}', [\App\Http\Controllers\Member\ProductsController::class, 'show'])->name('products.show');
    Route::get('/profile', \App\Http\Controllers\Member\ProfileController::class)->name('profile');
    Route::post('/profile/upload-avatar', [\App\Http\Controllers\Member\ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
