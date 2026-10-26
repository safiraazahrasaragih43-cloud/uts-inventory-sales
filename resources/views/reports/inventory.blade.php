@extends('layouts.app')

@section('title', 'Laporan Stok & Valuasi')
@section('page_title', 'Laporan Valuasi & Kesehatan Stok Inventaris (Admin)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4 no-print">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Valuasi Persediaan Busana</h3>
                <p class="text-xs text-gray-500">Analisis total modal aset yang tertanam pada stok toko pakaian dan deteksi stok kritis.</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan Stok</span>
            </button>
        </div>

        <form method="GET" action="{{ route('reports.inventory') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-blush-100">
            <div class="sm:col-span-8">
                <select name="filter_status" class="w-full px-3 py-2 bg-blush-50/40 rounded-xl border border-blush-200 text-xs text-gray-800">
                    <option value="">Semua Status Stok</option>
                    <option value="low" {{ request('filter_status') == 'low' ? 'selected' : '' }}>⚠️ Kritis / Menipis (<= Batas Minimum)</option>
                    <option value="out" {{ request('filter_status') == 'out' ? 'selected' : '' }}>Habis (0 pcs)</option>
                    <option value="safe" {{ request('filter_status') == 'safe' ? 'selected' : '' }}>Aman (> Batas Minimum)</option>
                </select>
            </div>
            <div class="sm:col-span-4 flex space-x-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-sm transition-colors">
                    Terapkan Filter
                </button>
                @if(request('filter_status'))
                    <a href="{{ route('reports.inventory') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 text-xs flex items-center justify-center">
                        ↺
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Fisik Pakaian</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalStockUnits, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">pcs</span></h3>
            <p class="text-[11px] text-gray-500 mt-1">Dari {{ $products->count() }} jenis busana</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Valuasi Modal (HPP)</p>
            <h3 class="text-2xl font-bold text-purple-900 mt-1">Rp {{ number_format($totalInventoryCost, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-500 mt-1">Total modal aset tertanam</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Potensi Nilai Jual Toko</p>
            <h3 class="text-2xl font-bold text-blush-800 mt-1">Rp {{ number_format($totalPotentialRevenue, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-500 mt-1">Bila seluruh persediaan terjual</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Produk Kritis (Low Stock)</p>
            <h3 class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">
                {{ $lowStockCount }} <span class="text-sm font-normal text-gray-500">produk</span>
            </h3>
            <p class="text-[11px] text-gray-500 mt-1">Memerlukan pemesanan ulang</p>
        </div>
    </div>

    <!-- Inventory Valuation Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-purple-50/70 text-purple-900 border-b border-purple-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">SKU & Busana</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4 text-center">Batas Min</th>
                        <th class="py-3.5 px-4 text-right">Harga Modal</th>
                        <th class="py-3.5 px-4 text-right">Valuasi Modal</th>
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-100">
                    @forelse($products as $p)
                        @php
                            $assetCost = $p->stock * $p->purchase_price;
                        @endphp
                        <tr class="hover:bg-purple-50/30 transition-colors {{ $p->isLowStock() ? 'bg-rose-50/20' : '' }}">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900 block">{{ $p->name }}</span>
                                <span class="font-mono text-[10px] text-gray-400">SKU: {{ $p->sku }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $p->category->name }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-bold text-sm {{ $p->stock <= 0 ? 'text-rose-600' : ($p->isLowStock() ? 'text-amber-600' : 'text-gray-800') }}">
                                    {{ $p->stock }} pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-500">
                                {{ $p->minimum_stock }} pcs
                            </td>
                            <td class="py-3.5 px-4 text-right text-gray-600 font-mono">
                                {{ $p->formatted_purchase_price }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-purple-900 font-mono">
                                Rp {{ number_format($assetCost, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-gray-800 font-mono">
                                {{ $p->formatted_selling_price }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($p->stock <= 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Habis
                                    </span>
                                @elseif($p->isLowStock())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Restock!
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">
                                Tidak ada data produk yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
@media print {
    aside, header, .no-print {
        display: none !important;
    }
}
</style>
@endsection
