@extends('layouts.app')

@section('title', 'Dashboard Ringkas')
@section('page_title', 'Ringkasan Toko & Inventory')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales Revenue -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-blush-900 mt-1">Rp {{ number_format($totalSalesAmount, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-500 mt-1">
                    Hari ini: <span class="font-semibold text-emerald-600">Rp {{ number_format($todaySalesAmount, 0, ',', '.') }}</span> ({{ $todaySalesCount }} trx)
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blush-100 text-blush-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Total Stock Units -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Unit Stok</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalStockUnits, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">pcs</span></h3>
                <p class="text-[11px] text-gray-500 mt-1">Dari {{ $totalProducts }} varian produk busana</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Peringatan Stok Menipis</p>
                <h3 class="text-2xl font-bold {{ $lowStockProductsCount > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">
                    {{ $lowStockProductsCount }} <span class="text-sm font-normal text-gray-500">produk</span>
                </h3>
                <p class="text-[11px] text-gray-500 mt-1">
                    {{ $lowStockProductsCount > 0 ? 'Butuh restock ke supplier segera' : 'Stok seluruh produk aman' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $lowStockProductsCount > 0 ? 'bg-rose-100 text-rose-600 animate-pulse' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- Inventory Valuation (Admin) or Total Transactions (Staff) -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm flex items-center justify-between">
            @if(auth()->user()->isAdmin())
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Nilai Aset Modal Inventory</p>
                    <h3 class="text-2xl font-bold text-blush-800 mt-1">Rp {{ number_format($totalInventoryAsset, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-gray-500 mt-1">Total modal baju di gudang & toko</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            @else
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Transaksi Kasir</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSalesCount }} trx</h3>
                    <p class="text-[11px] text-gray-500 mt-1">Status shift kasir aktif</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Action Bar -->
    <div class="bg-gradient-to-r from-blush-50 via-white to-blush-100 rounded-3xl p-6 border border-blush-200 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-blush-900">Aksi Cepat Operasional</h4>
            <p class="text-xs text-gray-500">Mulai transaksi kasir baru atau lakukan restock barang masuk.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('sales.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Kasir / Penjualan Baru</span>
            </a>
            <a href="{{ route('purchases.create') }}" class="px-4 py-2.5 rounded-xl bg-white text-blush-800 hover:bg-blush-50 transition-colors border border-blush-300 text-xs font-semibold flex items-center space-x-2 shadow-sm">
                <svg class="w-4 h-4 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                <span>Restock Pakaian (Purchase)</span>
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.create') }}" class="px-4 py-2.5 rounded-xl bg-blush-100 text-blush-900 hover:bg-blush-200 transition-colors text-xs font-semibold flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Produk Baru</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Low Stock Alert Warning Box (Critical) -->
    @if($lowStockItems->isNotEmpty())
        <div class="bg-rose-50/70 rounded-3xl p-6 border border-rose-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                    <h3 class="text-sm font-bold text-rose-900 uppercase tracking-wide">Perhatian: Produk Mendekati / Melewati Batas Minimum Stok</h3>
                </div>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs font-semibold text-rose-700 hover:text-rose-900 underline">
                    Lihat Semua ({{ $lowStockProductsCount }})
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-gray-500 border-b border-rose-200 pb-2">
                        <tr>
                            <th class="py-2 font-semibold">SKU</th>
                            <th class="py-2 font-semibold">Nama Produk</th>
                            <th class="py-2 font-semibold">Kategori</th>
                            <th class="py-2 font-semibold text-center">Stok Saat Ini</th>
                            <th class="py-2 font-semibold text-center">Min. Stok</th>
                            <th class="py-2 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rose-100">
                        @foreach($lowStockItems as $item)
                            <tr>
                                <td class="py-3 font-mono font-medium text-gray-700">{{ $item->sku }}</td>
                                <td class="py-3 font-semibold text-gray-900">{{ $item->name }}</td>
                                <td class="py-3 text-gray-600">{{ $item->category->name }}</td>
                                <td class="py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full font-bold {{ $item->stock <= 0 ? 'bg-rose-200 text-rose-900' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $item->stock }} pcs
                                    </span>
                                </td>
                                <td class="py-3 text-center text-gray-500">{{ $item->minimum_stock }} pcs</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('purchases.create') }}" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold transition-colors text-[11px]">
                                        Restock Segera
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recent Sales & Purchases Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Sales -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-blush-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Transaksi Penjualan Terbaru</h3>
                    <p class="text-xs text-gray-400">5 transaksi kasir terakhir</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-blush-600 hover:text-blush-800">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentSales as $sale)
                    <div class="p-3 rounded-2xl bg-blush-50/40 hover:bg-blush-50 border border-blush-100 transition-colors flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-semibold text-blush-800">{{ $sale->invoice_number }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white text-gray-600 border border-blush-200">{{ $sale->payment_method }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5">
                                {{ $sale->customer_name }} • {{ $sale->sale_date->translatedFormat('d M H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 block">{{ $sale->formatted_total_amount }}</span>
                            <a href="{{ route('sales.show', $sale) }}" class="text-[10px] text-blush-600 hover:underline">Detail Nota</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada transaksi penjualan tercatat.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Purchases -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-blush-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Pembelian Stok Terbaru</h3>
                    <p class="text-xs text-gray-400">5 transaksi restock terakhir</p>
                </div>
                <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-blush-600 hover:text-blush-800">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentPurchases as $purchase)
                    <div class="p-3 rounded-2xl bg-pastel-lavender/30 hover:bg-pastel-lavender/50 border border-purple-100 transition-colors flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-semibold text-purple-900">{{ $purchase->invoice_number }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5">
                                Supplier: <span class="font-medium text-gray-800">{{ $purchase->supplier->name }}</span>
                            </p>
                            <span class="text-[10px] text-gray-400">{{ $purchase->purchase_date->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 block">{{ $purchase->formatted_total_amount }}</span>
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-[10px] text-purple-600 hover:underline">Faktur Beli</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada transaksi pembelian stok tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
