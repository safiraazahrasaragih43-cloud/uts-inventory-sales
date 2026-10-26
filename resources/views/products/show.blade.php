@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)
@section('page_title', 'Informasi Detail Pakaian')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700 flex items-center space-x-1">
            <span>← Kembali ke Master Produk</span>
        </a>
        <div class="flex items-center space-x-2">
            <a href="{{ route('catalog.show', $product) }}" target="_blank" class="px-3 py-2 rounded-xl bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 border border-blush-200 shadow-sm flex items-center space-x-1">
                <span>Lihat Tampilan Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.edit', $product) }}" class="btn-blush-primary px-4 py-2 rounded-xl text-xs font-semibold">
                    Edit Produk Ini
                </a>
            @endif
        </div>
    </div>

    <!-- Product Card Info -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-blush-200/80 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <!-- Product Photo Column -->
            <div class="md:col-span-4">
                <div class="w-full h-64 sm:h-80 rounded-2xl bg-blush-50 border border-blush-200 overflow-hidden shadow-sm flex items-center justify-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-blush-300 p-4">
                            <svg class="w-16 h-16 mx-auto mb-2 text-blush-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-xs text-gray-400 font-medium">Belum ada foto produk</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details Column -->
            <div class="md:col-span-8 space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-1 rounded-full bg-blush-100 text-blush-800 text-[11px] font-semibold">
                        {{ $product->category->name }}
                    </span>
                    <span class="font-mono text-xs bg-gray-100 px-2.5 py-1 rounded-md text-gray-600">
                        SKU: {{ $product->sku }}
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

                <p class="text-xs text-gray-600 leading-relaxed">
                    {{ $product->description ?: 'Tidak ada deskripsi tambahan untuk produk ini.' }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-blush-100">
                    <div>
                        <span class="text-[11px] text-gray-400 block">Harga Jual Toko:</span>
                        <span class="text-lg font-bold text-blush-700">{{ $product->formatted_selling_price }}</span>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div>
                            <span class="text-[11px] text-gray-400 block">Harga Modal / Beli:</span>
                            <span class="text-lg font-bold text-gray-700">{{ $product->formatted_purchase_price }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-[11px] text-gray-400 block">Status Stok:</span>
                        <div class="flex items-center space-x-1.5 mt-0.5">
                            <span class="text-lg font-bold {{ $product->stock <= 0 ? 'text-rose-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-emerald-600') }}">
                                {{ $product->stock }} pcs
                            </span>
                            @if($product->isLowStock())
                                <span class="text-[10px] bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full font-semibold">
                                    Min: {{ $product->minimum_stock }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Restock Callout -->
            <div class="bg-blush-50/60 rounded-2xl p-6 border border-blush-200 flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold text-blush-900 mb-2">Restock Cepat</h4>
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        Perlu menambah persediaan baju ini? Buat transaksi pembelian stok langsung dari supplier.
                    </p>
                </div>
                <a href="{{ route('purchases.create') }}" class="w-full py-2.5 px-4 rounded-xl bg-blush-600 hover:bg-blush-700 text-white text-xs font-semibold text-center transition-colors shadow-sm">
                    Buat Form Restock
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction Ledger / History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sale History -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <h4 class="text-sm font-bold text-gray-900 mb-4">Riwayat Penjualan Produk Ini</h4>
            <div class="space-y-3">
                @forelse($product->saleItems as $item)
                    <div class="p-3 rounded-xl bg-blush-50/40 border border-blush-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-semibold text-blush-800">{{ $item->sale->invoice_number }}</span>
                            <p class="text-gray-500 text-[11px]">{{ $item->sale->customer_name }} • {{ $item->sale->sale_date->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-gray-900">{{ $item->quantity }} pcs</span>
                            <span class="block text-[11px] text-gray-500">{{ $item->formatted_subtotal }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-4 text-center">Belum ada riwayat penjualan produk ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Purchase History -->
        <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm">
            <h4 class="text-sm font-bold text-gray-900 mb-4">Riwayat Restock Masuk Produk Ini</h4>
            <div class="space-y-3">
                @forelse($product->purchaseItems as $item)
                    <div class="p-3 rounded-xl bg-purple-50/40 border border-purple-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-semibold text-purple-900">{{ $item->purchase->invoice_number }}</span>
                            <p class="text-gray-500 text-[11px]">Supplier: {{ $item->purchase->supplier->name }} • {{ $item->purchase->purchase_date->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-emerald-700">+{{ $item->quantity }} pcs</span>
                            <span class="block text-[11px] text-gray-500">{{ $item->formatted_subtotal }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-4 text-center">Belum ada riwayat pembelian stok produk ini.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
