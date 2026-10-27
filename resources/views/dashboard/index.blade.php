@extends('layouts.app')

@section('title', auth()->user()->isCustomer() ? 'Dashboard Pelanggan' : 'Dashboard Ringkas')
@section('page_title', auth()->user()->isCustomer() ? 'Portal Member Pelanggan' : 'Ringkasan Toko & Inventory')

@section('content')
@if(auth()->user()->isCustomer())
<div class="space-y-8">
    <!-- Welcome Customer Banner Pastel -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-blush-100 via-rose-50 to-sky-50 border border-blush-200 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/80 border border-blush-200 text-[#F27893] text-xs font-semibold shadow-xs">
                <span>🌸</span>
                <span>Member Prioritas BlushBoutique Club</span>
            </div>
            <h3 class="text-2xl font-bold text-blush-950">
                Selamat Datang, {{ auth()->user()->name }}!
            </h3>
            <p class="text-xs text-gray-600 leading-relaxed">
                Senang melihat Anda kembali. Di portal pelanggan ini, Anda dapat memantau riwayat belanja busana favorit, melihat struk nota resmi, dan menjelajahi katalog busana pastel terbaru.
            </p>
        </div>

        <div class="flex items-center space-x-3 flex-shrink-0">
            <a href="{{ route('catalog.index') }}" 
               class="inline-flex items-center space-x-2 px-5 py-3 rounded-2xl text-xs font-semibold text-white bg-gradient-to-r from-blush-500 to-blush-600 hover:from-blush-600 hover:to-blush-700 shadow-md shadow-blush-300/40 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Katalog Busana</span>
            </a>
            <a href="https://wa.me/6281234567890?text=Halo%20BlushBoutique,%20saya%20member%20{{ urlencode(auth()->user()->name) }}" 
               target="_blank"
               class="inline-flex items-center space-x-2 px-4 py-3 rounded-2xl text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.302c-.087.086-.179.18-.077.355.101.174.449.741.964 1.2.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.505.549-.679.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                <span>Bantuan WA</span>
            </a>
        </div>
    </div>

    <!-- Customer KPI Cards Pastel (Blush Pink, Light Blue, Soft Green, Soft Purple) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- 1. Total Belanja -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Akumulasi Belanja</p>
                <h3 class="text-2xl font-bold text-blush-900 mt-1">Rp {{ number_format($customerTotalSpent, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-500 mt-1">Riwayat belanja Anda</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blush-100 text-[#F27893] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- 2. Frekuensi Transaksi -->
        <div class="bg-white rounded-3xl p-6 border border-sky-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-sky-600">Total Transaksi Belanja</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $customerSales->count() }} <span class="text-sm font-normal text-gray-400">kali</span></h3>
                <p class="text-[11px] text-gray-500 mt-1">Faktur pembelian kasir</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EBF8FF] text-sky-600 flex items-center justify-center border border-sky-200/60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- 3. Koleksi Busana -->
        <div class="bg-white rounded-3xl p-6 border border-emerald-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Busana Dimiliki</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $customerTotalItems }} <span class="text-sm font-normal text-gray-400">pcs</span></h3>
                <p class="text-[11px] text-gray-500 mt-1">Koleksi fesyen terpilih</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F0FFF4] text-emerald-600 flex items-center justify-center border border-emerald-200/60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <!-- 4. Status Member -->
        <div class="bg-white rounded-3xl p-6 border border-purple-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-purple-600">Status Keanggotaan</p>
                <h3 class="text-xl font-bold text-purple-900 mt-1">Member VIP</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">✓ Akun Aktif & Terverifikasi</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-200/60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>
    </div>

    <!-- Riwayat Belanja Saya Section -->
    <div id="riwayat-belanja" class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-4 border-b border-blush-100">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#F27893]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Riwayat Transaksi Belanja Saya</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Daftar struk transaksi belanja busana Anda di kasir butik.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#FAF5F7] text-gray-600 font-semibold uppercase text-[11px] border-y border-blush-200">
                        <th class="py-3 px-4">No. Faktur</th>
                        <th class="py-3 px-4">Tanggal Pembelian</th>
                        <th class="py-3 px-4">Item Busana</th>
                        <th class="py-3 px-4">Total Belanja</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4 text-right">Struk Nota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($customerSales as $sale)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#F27893]">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $sale->sale_date->translatedFormat('d F Y, H:i') }} WIB
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-gray-800">{{ $sale->items->count() }} jenis busana</span>
                                <span class="text-gray-400">({{ $sale->items->sum('quantity') }} pcs)</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-gray-900">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('sales.show', $sale) }}" 
                                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-[#FAF5F7] hover:bg-blush-100 text-[#F27893] border border-blush-200 font-semibold text-[11px] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Struk</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center">
                                <div class="max-w-xs mx-auto space-y-2 text-gray-400">
                                    <p class="text-sm font-semibold text-gray-600">Belum Ada Transaksi Belanja</p>
                                    <p class="text-xs">Saat ini Anda belum memiliki riwayat pembelian di butik kami.</p>
                                    <div class="pt-2">
                                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#F27893] text-white font-semibold text-xs shadow-xs">
                                            <span>Mulai Belanja di Katalog</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rekomendasi Koleksi Busana Pastel Terbaru -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Koleksi Busana Rekomendasi Member</h3>
                <p class="text-xs text-gray-500">Pilihan terhangat musim ini bernuansa pastel lembut.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-[#F27893] hover:text-[#e54d71] flex items-center space-x-1">
                <span>Lihat Seluruh Koleksi</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($featuredProducts as $item)
                <div class="bg-white rounded-2xl p-4 border border-blush-200/80 shadow-sm space-y-3 hover:shadow-md transition-shadow">
                    <div class="w-full h-44 rounded-xl bg-[#FAF5F7] overflow-hidden flex items-center justify-center relative">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-[#F27893] text-xs font-semibold">Blush Boutique</div>
                        @endif
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/90 text-blush-700 shadow-xs">
                            Stok: {{ $item->stock }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-medium uppercase">{{ $item->category->name ?? 'Busana' }}</span>
                        <h4 class="text-xs font-bold text-gray-900 truncate" title="{{ $item->name }}">{{ $item->name }}</h4>
                        <p class="text-sm font-bold text-[#F27893] mt-1">{{ $item->formatted_selling_price }}</p>
                    </div>
                    <a href="{{ route('catalog.show', $item) }}" class="w-full py-2 rounded-xl bg-[#FAF5F7] hover:bg-blush-100 text-[#F27893] font-semibold text-xs flex items-center justify-center transition-colors">
                        <span>Lihat Detail Busana</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@else
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
            <a href="{{ route('customers.index') }}" class="px-4 py-2.5 rounded-xl bg-blush-50 text-blush-800 hover:bg-blush-100 transition-colors border border-blush-200 text-xs font-semibold flex items-center space-x-2 shadow-sm">
                <svg class="w-4 h-4 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Pelanggan ({{ $totalCustomers }})</span>
            </a>
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
                                @if($sale->customer)
                                    <a href="{{ route('customers.show', $sale->customer) }}" class="font-medium text-blush-700 hover:underline">{{ $sale->customer->name }}</a>
                                @else
                                    {{ $sale->customer_name }}
                                @endif
                                • {{ $sale->sale_date->translatedFormat('d M H:i') }}
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
@endif
@endsection
