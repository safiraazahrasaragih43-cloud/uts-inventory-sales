@extends('layouts.app')

@section('title', 'Nota Penjualan: ' . $sale->invoice_number)
@section('page_title', 'Faktur Penjualan Kasir')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between no-print">
        <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
            ← Riwayat Penjualan
        </a>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-blush-600 hover:bg-blush-700 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Struk / Nota</span>
            </button>
            <a href="{{ route('sales.create') }}" class="btn-blush-primary px-4 py-2 rounded-xl text-xs font-semibold">
                + Kasir Baru
            </a>
        </div>
    </div>

    <!-- Printable Receipt Box -->
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-md printable-receipt">
        <!-- Boutique Header -->
        <div class="text-center pb-6 border-b border-blush-100">
            <div class="w-12 h-12 rounded-2xl bg-blush-500 text-white flex items-center justify-center mx-auto mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-blush-900 font-serif">BlushBoutique</h2>
            <p class="text-[11px] text-gray-500">Jl. Kenanga Indah No. 18, Bandung • Telp/WA: 0812-9876-5432</p>
            <span class="inline-block mt-2 font-mono text-xs font-bold text-blush-800 bg-blush-50 px-3 py-1 rounded-full border border-blush-200">
                FAKTUR RESMI: {{ $sale->invoice_number }}
            </span>
        </div>

        <!-- Meta Information -->
        <div class="grid grid-cols-2 gap-4 py-4 border-b border-blush-100 text-xs">
            <div>
                <p class="text-gray-400">Tanggal Transaksi:</p>
                <p class="font-semibold text-gray-800">{{ $sale->sale_date->translatedFormat('d F Y, H:i') }} WIB</p>
                <p class="text-gray-400 mt-2">Kasir Bertugas:</p>
                <p class="font-semibold text-gray-800">{{ $sale->user->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400">Nama Pelanggan:</p>
                @if($sale->customer)
                    <p class="font-semibold text-gray-800">
                        {{ $sale->customer->name }}
                        <span class="inline-block text-[9px] px-1.5 py-0.5 rounded-full bg-blush-100 text-blush-700 font-bold border border-blush-200">Member</span>
                    </p>
                    @if($sale->customer->phone)
                        <p class="text-[11px] text-gray-500 font-mono">{{ $sale->customer->phone }}</p>
                    @endif
                @else
                    <p class="font-semibold text-gray-800">{{ $sale->customer_name ?: 'Walk-in Customer' }}</p>
                    <p class="text-[10px] text-gray-400">Walk-in Customer</p>
                @endif
                <p class="text-gray-400 mt-2">Metode Pembayaran:</p>
                <p class="font-semibold text-emerald-700">{{ $sale->payment_method }}</p>
            </div>
        </div>

        <!-- Itemized Table -->
        <div class="py-4 border-b border-blush-100">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-gray-400 text-left border-b border-blush-50 pb-2">
                        <th class="py-2">Item Busana</th>
                        <th class="py-2 text-center">Qty</th>
                        <th class="py-2 text-right">Harga</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-50">
                    @foreach($sale->items as $item)
                        <tr>
                            <td class="py-3">
                                <span class="font-bold text-gray-900 block">{{ $item->product->name }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">SKU: {{ $item->product->sku }}</span>
                            </td>
                            <td class="py-3 text-center font-semibold text-gray-800">
                                {{ $item->quantity }}
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
            <div class="flex justify-between items-center text-sm font-bold text-blush-900 pt-2">
                <span>TOTAL AKHIR:</span>
                <span class="text-xl text-blush-700">{{ $sale->formatted_total_amount }}</span>
            </div>
        </div>

        @if($sale->notes)
            <div class="p-3 bg-blush-50 rounded-xl text-[11px] text-gray-600 mb-6">
                <span class="font-semibold">Catatan:</span> {{ $sale->notes }}
            </div>
        @endif

        <!-- Footer Note -->
        <div class="text-center pt-6 text-[11px] text-gray-400 space-y-1">
            <p>Terima kasih telah berbelanja di <span class="font-semibold text-blush-700">BlushBoutique</span> 🌸</p>
            <p>Barang yang sudah dibeli dapat ditukar maksimal 2 hari dengan membawa nota ini.</p>
        </div>
    </div>

</div>

<style>
@media print {
    body {
        background: white !important;
    }
    aside, header, .no-print {
        display: none !important;
    }
    .printable-receipt {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
}
</style>
@endsection
