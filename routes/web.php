<?php

use App\Http\Controllers\TokoqInventoryController;
use App\Http\Controllers\TokoqOnboardingController;
use App\Http\Controllers\TokoqPageController;
use App\Http\Controllers\TokoqSaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TokoqPageController::class, 'landing'])->name('landing');
Route::get('/onboarding', [TokoqOnboardingController::class, 'create'])->name('onboarding');
Route::post('/onboarding', [TokoqOnboardingController::class, 'store'])->name('onboarding.store');

Route::middleware('store.onboarded')->group(function (): void {
    Route::get('/dashboard', [TokoqPageController::class, 'dashboard'])->name('dashboard');
    Route::get('/kasir', [TokoqPageController::class, 'pos'])->name('pos');
    Route::post('/kasir/checkout', [TokoqSaleController::class, 'checkout'])->name('pos.checkout');
    Route::get('/inventaris', [TokoqPageController::class, 'inventory'])->name('inventory');
    Route::post('/inventaris/produk', [TokoqInventoryController::class, 'store'])->name('inventory.products.store');
    Route::get('/analisis-penjualan', [TokoqPageController::class, 'sales'])->name('sales');
    Route::get('/laporan', [TokoqPageController::class, 'reports'])->name('reports');
    Route::get('/prediksi-ai', [TokoqPageController::class, 'forecast'])->name('forecast');
});
