<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - BlushBoutique Inventory & Sales Management
|--------------------------------------------------------------------------
*/

// 1. Tampilan Publik untuk Pengunjung / Pelanggan (Tanpa Perlu Login)
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// Alur Pemesanan Publik COD (Cash on Delivery)
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{sale:invoice_number}', [CheckoutController::class, 'success'])->name('checkout.success');

// 2. Autentikasi Admin & Staff
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. Area Autentikasi (Admin & Staff)
Route::middleware(['auth'])->group(function () {
    // Dashboard Ringkas
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Operasional Kasir, Restock, dan Kelola Customer (Khusus Staff Kasir & Admin)
    Route::middleware(['role:admin,staff'])->group(function () {
        // Transaksi Penjualan (Sales) - Kasir & Admin
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');

        // Master Customer / Pelanggan
        Route::resource('customers', CustomerController::class);

        // Transaksi Pembelian Stok (Purchases) - Restock
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    });

    // Detail Faktur Nota Struk (Dapat diakses Admin, Staff, dan Customer)
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Master Produk (Staff hanya bisa melihat daftar & detail produk)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 4. Khusus Hak Akses ADMIN / PEMILIK (Kelola Master Data & Laporan)
    Route::middleware(['role:admin'])->group(function () {
        // Kelola Produk (Tambah, Ubah, Hapus)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Master Kategori
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Master Supplier
        Route::resource('suppliers', SupplierController::class)->except(['show']);

        // Laporan Lengkap
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    });

    // Detail Produk (Dapat diakses oleh Admin & Staff)
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});
