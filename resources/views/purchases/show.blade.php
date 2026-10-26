@extends('layouts.app')

@section('title', 'Faktur Pembelian: ' . $purchase->invoice_number)
@section('page_title', 'Detail Transaksi Restock / Pembelian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
            ← Riwayat Pembelian
        </a>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Faktur</span>
            </button>
            <a href="{{ route('purchases.create') }}" class="btn-blush-primary px-4 py-2 rounded-xl text-xs font-semibold">
                + Restock Baru
            </a>
        </div>
    </div>

    <!-- Purchase Invoice Detail Box -->
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-sm printable-receipt">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-blush-100 gap-4">
            <div>
                <span class="font-mono text-xs font-bold text-purple-900 bg-purple-100 px-3 py-1 rounded-full border border-purple-200">
                    {{ $purchase->invoice_number }}
                </span>
                <h2 class="text-lg font-bold text-gray-900 mt-2">Faktur Penerimaan Barang Restock</h2>
                <p class="text-xs text-gray-500">Stok telah berhasil diinput ke inventory BlushBoutique.</p>
            </div>
            <div class="text-left sm:text-right text-xs">
                <p class="text-gray-400">Tanggal Transaksi:</p>
                <p class="font-bold text-gray-800">{{ $purchase->purchase_date->translatedFormat('d F Y') }}</p>
                <p class="text-gray-400 mt-1">Dicatat Oleh: <span class="font-semibold text-gray-700">{{ $purchase->user->name }}</span></p>
            </div>
        </div>

        <!-- Supplier Details -->
        <div class="my-6 p-4 rounded-2xl bg-purple-50/50 border border-purple-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <p class="text-gray-400 font-medium">Informasi Supplier / Mitra:</p>
                <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $purchase->supplier->name }}</p>
                <p class="text-gray-600 mt-1">{{ $purchase->supplier->address ?: 'Alamat tidak dicatat' }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-gray-400 font-medium">Kontak Supplier:</p>
                <p class="text-gray-700 mt-0.5">PIC: {{ $purchase->supplier->contact_person ?: '-' }}</p>
                <p class="text-purple-700 font-semibold">{{ $purchase->supplier->phone }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-4 border-b border-blush-100">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 text-left border-b border-blush-50 pb-2">
                        <th class="py-2">Item Pakaian</th>
                        <th class="py-2 text-center">Jumlah Masuk</th>
                        <th class="py-2 text-right">Harga Modal Satuan</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-50">
                    @foreach($purchase->items as $item)
                        <tr>
                            <td class="py-3">
                                <span class="font-bold text-gray-900 block">{{ $item->product->name }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">SKU: {{ $item->product->sku }} • {{ $item->product->category->name }}</span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    +{{ $item->quantity }} pcs
                                </span>
                            </td>
                            <td class="py-3 text-right text-gray-600">
                                {{ $item->formatted_unit_price }}
                            </td>
                            <td class="py-3 text-right font-bold text-gray-900">
                                {{ $item->formatted_subtotal }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Grand Total Calculation -->
        <div class="py-4 space-y-2 text-xs">
            <div class="flex justify-between items-center text-sm font-bold text-purple-900 pt-2">
                <span>TOTAL TAGIHAN RESTOCK:</span>
                <span class="text-xl text-purple-700">{{ $purchase->formatted_total_amount }}</span>
            </div>
        </div>

        @if($purchase->notes)
            <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-600 mt-4">
                <span class="font-semibold">Catatan Transaksi:</span> {{ $purchase->notes }}
            </div>
        @endif
    </div>

</div>
@endsection
