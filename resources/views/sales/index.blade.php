@extends('layouts.app')

@section('title', 'Riwayat Penjualan')
@section('page_title', 'Riwayat Transaksi Penjualan Kasir')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Catatan Penjualan Pakaian</h3>
                <p class="text-xs text-gray-500">Semua transaksi penjualan otomatis mengurangi stok produk secara konsisten.</p>
            </div>
            <a href="{{ route('sales.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Transaksi Kasir Baru</span>
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-blush-100">
            <!-- Keyword Search -->
            <div class="sm:col-span-4 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor faktur atau nama pelanggan..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Payment Method Filter -->
            <div class="sm:col-span-3">
                <select name="payment_method" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                    <option value="">Semua Metode Pembayaran</option>
                    <option value="Tunai" {{ request('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    <option value="Transfer Bank" {{ request('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="Debit/Kartu Kredit" {{ request('payment_method') == 'Debit/Kartu Kredit' ? 'selected' : '' }}>Debit/Kartu Kredit</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="sm:col-span-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800" title="Dari Tanggal">
            </div>

            <!-- Date To -->
            <div class="sm:col-span-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800" title="Sampai Tanggal">
            </div>

            <!-- Filter Buttons -->
            <div class="sm:col-span-1 flex space-x-1">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-blush-500 hover:bg-blush-600 text-white text-xs font-semibold flex items-center justify-center shadow-sm">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">No. Faktur</th>
                        <th class="py-3.5 px-4">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Kasir / Staff</th>
                        <th class="py-3.5 px-4">Metode Bayar</th>
                        <th class="py-3.5 px-4 text-center">Item Baju</th>
                        <th class="py-3.5 px-4 text-right">Total Transaksi</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-blush-800">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $sale->sale_date->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-gray-800">
                                @if($sale->customer)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="font-semibold text-gray-900">{{ $sale->customer->name }}</span>
                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-blush-100 text-blush-700 border border-blush-200">Member</span>
                                    </div>
                                    @if($sale->customer->phone)
                                        <span class="text-[10px] text-gray-400 font-mono block">{{ $sale->customer->phone }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-700">{{ $sale->customer_name ?: 'Walk-in Customer' }}</span>
                                    <span class="text-[10px] text-gray-400 block">Umum / Walk-in</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-semibold text-gray-700">
                                    {{ $sale->user->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-blush-100 text-blush-900 font-semibold text-[10px]">
                                    {{ $sale->items->sum('quantity') }} pcs ({{ $sale->items->count() }} item)
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-gray-900 text-sm">
                                {{ $sale->formatted_total_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800 font-semibold text-[11px] transition-colors">
                                    <span>Cetak Nota</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">
                                Belum ada riwayat transaksi penjualan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $sales->links() }}
        </div>
    </div>

</div>
@endsection
