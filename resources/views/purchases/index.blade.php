@extends('layouts.app')

@section('title', 'Riwayat Pembelian Stok')
@section('page_title', 'Riwayat Transaksi Restock (Pembelian dari Supplier)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Catatan Restock Barang Masuk</h3>
                <p class="text-xs text-gray-500">Mencatat pembelian stok pakaian dari pabrik & supplier untuk menambah inventaris.</p>
            </div>
            <a href="{{ route('purchases.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Restock / Pembelian Baru</span>
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('purchases.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-blush-100">
            <!-- Keyword Search -->
            <div class="sm:col-span-4 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari invoice pembelian atau nama supplier..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Supplier Filter -->
            <div class="sm:col-span-3">
                <select name="supplier_id" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
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

    <!-- Purchases Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">No. Invoice Beli</th>
                        <th class="py-3.5 px-4">Tanggal Pembelian</th>
                        <th class="py-3.5 px-4">Supplier / Mitra</th>
                        <th class="py-3.5 px-4">Dicatat Oleh</th>
                        <th class="py-3.5 px-4 text-center">Total Unit Masuk</th>
                        <th class="py-3.5 px-4 text-right">Total Tagihan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-purple-900">
                                {{ $purchase->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $purchase->purchase_date->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-900">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-semibold text-gray-700">
                                    {{ $purchase->user->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                                    +{{ $purchase->items->sum('quantity') }} pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-gray-900 text-sm">
                                {{ $purchase->formatted_total_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('purchases.show', $purchase) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-800 font-semibold text-[11px] transition-colors">
                                    <span>Detail Faktur</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">
                                Belum ada riwayat transaksi pembelian stok yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $purchases->links() }}
        </div>
    </div>

</div>
@endsection
