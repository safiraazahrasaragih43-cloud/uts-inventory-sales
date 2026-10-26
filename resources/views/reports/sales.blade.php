@extends('layouts.app')

@section('title', 'Laporan Laba & Penjualan')
@section('page_title', 'Laporan Analisis Penjualan & Keuntungan (Admin)')

@section('content')
<div class="space-y-6">

    <!-- Date Range Filter & Header -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4 no-print">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Filter Periode Laporan Keuangan</h3>
                <p class="text-xs text-gray-500">Kalkulasi omset, modal pembelian barang (HPP/COGS), dan estimasi laba kotor.</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-blush-600 hover:bg-blush-700 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>

        <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-blush-100">
            <div class="sm:col-span-5">
                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Mulai Tanggal:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 bg-blush-50/40 rounded-xl border border-blush-200 text-xs text-gray-800">
            </div>
            <div class="sm:col-span-5">
                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Sampai Tanggal:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 bg-blush-50/40 rounded-xl border border-blush-200 text-xs text-gray-800">
            </div>
            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full py-2 px-4 rounded-xl btn-blush-primary text-xs font-bold shadow-sm">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Pendapatan (Omset)</p>
            <h3 class="text-2xl font-bold text-blush-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-500 mt-1">{{ $totalTransactions }} transaksi penjualan</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Estimasi Modal Baju (HPP)</p>
            <h3 class="text-2xl font-bold text-gray-700 mt-1">Rp {{ number_format($totalCogs, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-500 mt-1">Berdasarkan purchase_price master</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Estimasi Laba Kotor</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-500 mt-1">
                Margin: {{ $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0 }}%
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Rata-rata Nilai Transaksi</p>
            <h3 class="text-2xl font-bold text-purple-900 mt-1">
                Rp {{ $totalTransactions > 0 ? number_format($totalRevenue / $totalTransactions, 0, ',', '.') : 0 }}
            </h3>
            <p class="text-[11px] text-gray-500 mt-1">Per tiket penjualan kasir</p>
        </div>
    </div>

    <!-- Sales Breakdown Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-blush-100 bg-blush-50/50">
            <h4 class="text-sm font-bold text-blush-900">Rincian Transaksi Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3 px-4">No. Faktur</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Item Terjual</th>
                        <th class="py-3 px-4 text-right">Modal (HPP)</th>
                        <th class="py-3 px-4 text-right">Pendapatan</th>
                        <th class="py-3 px-4 text-right">Laba</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($sales as $s)
                        @php
                            $cogs = $s->items->sum(function($item) {
                                return ($item->product ? $item->product->purchase_price : 0) * $item->quantity;
                            });
                            $profit = $s->total_amount - $cogs;
                        @endphp
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-blush-800">
                                <a href="{{ route('sales.show', $s) }}" class="hover:underline">
                                    {{ $s->invoice_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ $s->sale_date->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 text-gray-800 font-medium">
                                {{ $s->customer_name }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                @foreach($s->items as $it)
                                    <div class="text-[11px]">• {{ $it->product->name }} ({{ $it->quantity }}x)</div>
                                @endforeach
                            </td>
                            <td class="py-3 px-4 text-right text-gray-600 font-mono">
                                Rp {{ number_format($cogs, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-gray-900 font-mono">
                                {{ $s->formatted_total_amount }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-emerald-700 font-mono">
                                +Rp {{ number_format($profit, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">
                                Tidak ada data transaksi penjualan pada rentang tanggal yang dipilih.
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
