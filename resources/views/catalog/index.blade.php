@extends('layouts.public')

@section('content')
<!-- Custom Styles for CSS Flexbox Catalog & 3:4 Ratio -->
<style>
    .catalog-flex-wrapper {
        display: flex;
        flex-wrap: wrap;
        margin-left: -0.875rem;
        margin-right: -0.875rem;
    }
    .catalog-flex-col {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding-left: 0.875rem;
        padding-right: 0.875rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 640px) {
        .catalog-flex-col {
            width: 50%;
        }
    }
    @media (min-width: 1024px) {
        .catalog-flex-col {
            width: 33.333333%;
        }
    }
    .fashion-img-container {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background-color: #FDF2F4;
    }
    .fashion-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .catalog-card:hover .fashion-img {
        transform: scale(1.06);
    }
    .btn-add-cart {
        background: linear-gradient(135deg, #f27893 0%, #e54d71 100%);
        box-shadow: 0 4px 14px rgba(229, 77, 113, 0.28);
        transition: all 0.25s ease;
    }
    .btn-add-cart:hover {
        background: linear-gradient(135deg, #e54d71 0%, #d03058 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(229, 77, 113, 0.4);
    }
    .btn-add-cart:active {
        transform: translateY(0);
    }
    .cart-drawer-overlay {
        background: rgba(43, 29, 36, 0.45);
        backdrop-filter: blur(6px);
        transition: opacity 0.3s ease;
    }
    .cart-drawer {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-blush-100/70 via-pastel-rose to-blush-50 py-16 sm:py-20 border-b border-blush-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block py-1.5 px-4 rounded-full text-xs font-semibold tracking-wider uppercase bg-white/90 text-blush-700 border border-blush-300 shadow-sm mb-4">
            ✨ Koleksi Fesyen Butik Eksklusif
        </span>
        <h1 class="font-serif-luxury text-4xl sm:text-5xl md:text-6xl font-bold text-blush-900 tracking-tight leading-tight max-w-3xl mx-auto mb-5">
            Pesona Anggun dalam Balutan Warna <span class="italic text-blush-600">Soft Pastel</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed mb-8">
            Temukan aneka gaun pesta anggun, blus sutra floral, kemeja flanel trendi, knitwear, dan hijab premium. Pesan langsung dengan mudah di website, pembayaran COD (Bayar di Tempat) saat pesanan tiba.
        </p>

        <!-- Quick Info Badges -->
        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 text-xs font-medium text-blush-900">
            <span class="flex items-center space-x-1.5 bg-white/80 px-4 py-2 rounded-xl border border-blush-200 shadow-sm">
                <span>🛍️</span> <span>Foto Asli Beresolusi Tinggi</span>
            </span>
            <span class="flex items-center space-x-1.5 bg-white/80 px-4 py-2 rounded-xl border border-blush-200 shadow-sm">
                <span>📦</span> <span>Stok Realtime Terjamin</span>
            </span>
            <span class="flex items-center space-x-1.5 bg-white/80 px-4 py-2 rounded-xl border border-blush-200 shadow-sm">
                <span>🚚</span> <span>Bayar di Tempat (COD)</span>
            </span>
            <span class="flex items-center space-x-1.5 bg-white/80 px-4 py-2 rounded-xl border border-blush-200 shadow-sm">
                <span>📍</span> <span>Showroom Fisik di Bandung</span>
            </span>
        </div>
    </div>
</section>

<!-- Filter & Search Toolbar (Flexbox Powered) -->
<section id="koleksi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
    <div class="bg-white rounded-3xl p-6 shadow-xl shadow-blush-500/5 border border-blush-100">
        <form method="GET" action="{{ route('catalog.index') }}" class="space-y-4">
            <div class="flex flex-wrap -mx-2 items-center">
                <!-- Search Keyword -->
                <div class="w-full md:w-5/12 px-2 mb-3 md:mb-0">
                    <div class="relative">
                        <input type="text" name="q" id="searchKeyword" value="{{ request('q') }}" placeholder="Cari gaun maroon, kemeja flanel, blus floral..." class="w-full pl-11 pr-4 py-3 bg-blush-50/50 rounded-2xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-sm text-gray-800 placeholder-gray-400 transition-all">
                        <svg class="w-5 h-5 text-blush-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-1/2 md:w-3/12 px-2 mb-3 md:mb-0">
                    <select name="category" onchange="this.form.submit()" class="w-full px-4 py-3 bg-blush-50/50 rounded-2xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-sm text-gray-800 cursor-pointer">
                        <option value="">Semua Kategori Busana</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="w-1/2 md:w-2/12 px-2 mb-3 md:mb-0">
                    <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-3 bg-blush-50/50 rounded-2xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-sm text-gray-800 cursor-pointer">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama: A - Z</option>
                    </select>
                </div>

                <!-- Action Button -->
                <div class="w-full md:w-2/12 px-2 flex space-x-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-2xl btn-add-cart text-white text-xs font-bold tracking-wide flex items-center justify-center space-x-1.5 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['q', 'category', 'sort', 'status']))
                        <a href="{{ route('catalog.index') }}" class="p-3 bg-gray-100 hover:bg-gray-200 rounded-2xl text-gray-600 flex items-center justify-center transition-colors" title="Reset Semua Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Category Quick Pills using Flexbox -->
            <div id="kategori" class="flex flex-wrap items-center gap-2 pt-3 border-t border-blush-50">
                <span class="text-xs font-semibold text-gray-500 mr-1 flex items-center space-x-1">
                    <span>👗</span> <span>Pilihan:</span>
                </span>
                <a href="{{ route('catalog.index') }}" class="text-xs px-3.5 py-1.5 rounded-full font-medium transition-all {{ !request('category') ? 'bg-blush-600 text-white shadow-sm' : 'bg-blush-50 text-blush-800 hover:bg-blush-100' }}">
                    Semua Koleksi
                </a>
                @foreach($categories as $c)
                    <a href="{{ route('catalog.index', ['category' => $c->slug]) }}" class="text-xs px-3.5 py-1.5 rounded-full font-medium transition-all {{ request('category') == $c->slug ? 'bg-blush-600 text-white shadow-sm' : 'bg-blush-50 text-blush-800 hover:bg-blush-100' }}">
                        {{ $c->name }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>
</section>

<!-- Product Catalog Section (Implemented strictly with CSS Flexbox & 3:4 Uniform Aspect Ratio) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header info -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blush-500 animate-pulse"></span>
                <span class="text-xs font-semibold tracking-wider uppercase text-blush-600">Butik Fesyen Terpilih</span>
            </div>
            <h2 class="font-serif-luxury text-2xl sm:text-3xl font-bold text-blush-900 mt-1">
                Koleksi Pakaian Pilihan
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Menampilkan {{ $products->total() }} busana pilihan dengan foto asli beresolusi tinggi (Aspek Rasio 3:4)
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <span class="text-xs font-medium text-blush-700 bg-blush-50 px-3.5 py-1.5 rounded-full border border-blush-200 shadow-sm">
                Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
            </span>
            <button type="button" onclick="openCartDrawer()" class="btn-add-cart text-white text-xs font-semibold px-4 py-2 rounded-xl flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Lihat Keranjang</span>
                <span id="badgeCartBtn" class="px-1.5 py-0.2 rounded-full bg-white text-blush-800 text-[10px] font-bold">0</span>
            </button>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-blush-100 shadow-sm max-w-xl mx-auto">
            <div class="w-16 h-16 bg-blush-100 rounded-full flex items-center justify-center mx-auto text-blush-500 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-serif-luxury text-xl font-bold text-gray-800 mb-2">Pakaian Tidak Ditemukan</h3>
            <p class="text-sm text-gray-500 mb-6">Maaf, kami tidak dapat menemukan koleksi pakaian yang sesuai dengan kata kunci pencarian atau filter yang dipilih.</p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold px-6 py-3 rounded-2xl btn-add-cart text-white shadow-md">
                <span>Lihat Semua Koleksi Butik</span>
            </a>
        </div>
    @else
        <!-- CSS Flexbox Catalog Container -->
        <div class="catalog-flex-wrapper" id="productsFlexGrid">
            @foreach($products as $product)
                @php
                    // High-res boutique product image
                    $imageUrl = $product->image_url ?: 'https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=800&q=80';
                @endphp

                <!-- Flexbox Column Item -->
                <div class="catalog-flex-col product-item-card" data-id="{{ $product->id }}" data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category->slug }}">
                    <div class="catalog-card bg-white rounded-3xl overflow-hidden border border-blush-100/90 shadow-md hover:shadow-2xl hover:border-blush-300 transition-all duration-300 flex flex-col h-full group">
                        
                        <!-- 1. Product Image with strict 3:4 Uniform Aspect Ratio -->
                        <div class="fashion-img-container">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product->name }}"
                                 class="fashion-img"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80';">

                            <!-- Category Badge Overlay (Top Left) -->
                            <div class="absolute top-3.5 left-3.5 z-10">
                                <span class="text-[11px] font-semibold text-blush-900 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full border border-blush-200 shadow-sm flex items-center space-x-1">
                                    <span>🌸</span>
                                    <span>{{ $product->category->name }}</span>
                                </span>
                            </div>

                            <!-- Stock Status Badge (Top Right) -->
                            <div class="absolute top-3.5 right-3.5 z-10">
                                @if($product->stock > 5)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-500/95 backdrop-blur-md text-white shadow-sm flex items-center space-x-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        <span>Ready ({{ $product->stock }})</span>
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-amber-500/95 backdrop-blur-md text-white shadow-sm flex items-center space-x-1">
                                        <span>Sisa {{ $product->stock }}</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-rose-600/95 backdrop-blur-md text-white shadow-sm">
                                        Habis
                                    </span>
                                @endif
                            </div>

                            <!-- SKU Tag (Bottom Left) -->
                            <div class="absolute bottom-3 left-3 z-10">
                                <span class="font-mono text-[10px] text-gray-700 bg-white/85 backdrop-blur-md px-2 py-0.5 rounded-md border border-gray-200 shadow-sm">
                                    {{ $product->sku }}
                                </span>
                            </div>

                            <!-- Quick View Overlay Button on Hover -->
                            <div class="absolute inset-0 bg-blush-900/20 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <button type="button"
                                        onclick="openQuickView({{ json_encode([
                                            'id' => $product->id,
                                            'name' => $product->name,
                                            'sku' => $product->sku,
                                            'category' => $product->category->name,
                                            'price' => $product->selling_price,
                                            'formatted_price' => $product->formatted_selling_price,
                                            'stock' => $product->stock,
                                            'description' => $product->description,
                                            'image' => $imageUrl,
                                        ]) }})"
                                        class="px-4 py-2 bg-white/95 hover:bg-white text-blush-800 rounded-full text-xs font-bold shadow-lg transform -translate-y-2 group-hover:translate-y-0 transition-all flex items-center space-x-1.5">
                                    <svg class="w-4 h-4 text-blush-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Pratinjau Cepat</span>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Product Details & Call-To-Action (Card Body) -->
                        <div class="p-5 flex-grow flex flex-col justify-between bg-white">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blush-500 block mb-1">
                                    {{ $product->category->name }}
                                </span>
                                
                                <h3 class="font-serif-luxury text-base sm:text-lg font-bold text-gray-900 group-hover:text-blush-600 transition-colors leading-snug line-clamp-1 mb-1.5" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                                
                                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-3">
                                    {{ $product->description ?: 'Koleksi busana berkualitas istimewa berpadu kenyamanan dan keanggunan gaya feminin.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-blush-100/70">
                                <!-- Price display -->
                                <div class="flex items-baseline justify-between mb-3.5">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-medium block">Harga Butik</span>
                                        <span class="text-lg sm:text-xl font-bold text-blush-900 font-serif-luxury">
                                            {{ $product->formatted_selling_price }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-gray-400 font-mono block">Status</span>
                                        <span class="text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                            {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Elegant Action Buttons using Flexbox -->
                                <div class="flex flex-col sm:flex-row items-center gap-2">
                                    <!-- Primary: Tambah ke Keranjang -->
                                    <button type="button"
                                            onclick="addToCart({{ json_encode([
                                                'id' => $product->id,
                                                'name' => $product->name,
                                                'sku' => $product->sku,
                                                'price' => (float) $product->selling_price,
                                                'formatted_price' => $product->formatted_selling_price,
                                                'image' => $imageUrl,
                                                'stock' => $product->stock,
                                            ]) }})"
                                            class="w-full sm:flex-1 btn-add-cart py-2.5 px-3 rounded-xl text-white text-xs font-bold tracking-wide flex items-center justify-center space-x-1.5 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        <span>Tambah ke Keranjang</span>
                                    </button>

                                    <!-- Direct Order COD -->
                                    <a href="{{ route('checkout.show', ['product_id' => $product->id]) }}"
                                       class="w-full sm:w-auto py-2.5 px-3.5 rounded-xl bg-blush-50 hover:bg-blush-100 text-blush-800 border border-blush-200 transition-colors flex items-center justify-center space-x-1.5 font-semibold text-xs {{ $product->stock <= 0 ? 'pointer-events-none opacity-50' : '' }}"
                                       title="Pesan Sekarang">
                                        <svg class="w-4 h-4 text-blush-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>Pesan Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
</section>

<!-- Slide-Over Shopping Cart Drawer (Keranjang Belanja) -->
<div id="cartDrawerBackdrop" class="fixed inset-0 z-50 hidden cart-drawer-overlay opacity-0">
    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div id="cartDrawerPanel" class="w-screen max-w-md bg-white shadow-2xl flex flex-col cart-drawer transform translate-x-full">
            <!-- Drawer Header -->
            <div class="p-6 bg-gradient-to-r from-blush-50 to-pastel-rose border-b border-blush-200 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blush-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-serif-luxury text-lg font-bold text-blush-900">Keranjang Pesanan</h3>
                        <p class="text-xs text-gray-500">Metode pembayaran COD (Bayar di Tempat)</p>
                    </div>
                </div>
                <button type="button" onclick="closeCartDrawer()" class="p-2 rounded-xl text-gray-400 hover:text-blush-700 hover:bg-blush-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Items Body -->
            <div class="flex-grow overflow-y-auto p-6 space-y-4" id="cartItemsList">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Drawer Footer: Checkout Action Buttons -->
            <div class="p-6 bg-blush-50/50 border-t border-blush-200 space-y-4">
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Total Jumlah Item:</span>
                        <span id="cartTotalItemsCount" class="font-semibold text-gray-800">0 pcs</span>
                    </div>
                    <div class="flex justify-between items-baseline pt-1">
                        <span class="text-sm font-bold text-gray-800">Total Pembayaran:</span>
                        <span id="cartGrandTotalText" class="text-2xl font-bold font-serif-luxury text-blush-900">Rp 0</span>
                    </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-blush-100">
                    <button type="button" onclick="proceedToCheckout()" class="w-full py-3.5 px-4 rounded-2xl blush-btn text-white font-bold text-sm shadow-lg shadow-blush-500/25 transition-all flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Pesan Sekarang (Checkout COD)</span>
                    </button>
                    <button type="button" onclick="clearCart()" class="w-full text-center text-xs text-gray-400 hover:text-rose-500 py-1 transition-colors">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-blush-200 transform scale-95 transition-transform duration-300 flex flex-col md:flex-row" id="quickViewCard">
        <!-- 3:4 Image Column -->
        <div class="w-full md:w-1/2 fashion-img-container" style="aspect-ratio: 3/4;">
            <img id="qvImage" src="" alt="" class="fashion-img">
        </div>
        <!-- Info Column -->
        <div class="w-full md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span id="qvCategory" class="text-[11px] font-bold text-blush-600 bg-blush-50 px-2.5 py-0.5 rounded-full border border-blush-200 uppercase tracking-wide"></span>
                    <button type="button" onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <h3 id="qvTitle" class="font-serif-luxury text-xl font-bold text-gray-900 leading-tight mb-1"></h3>
                <span id="qvSku" class="font-mono text-[10px] text-gray-400 block mb-3"></span>
                <p id="qvPrice" class="text-2xl font-bold text-blush-900 font-serif-luxury mb-4"></p>
                <p id="qvDescription" class="text-xs text-gray-600 leading-relaxed mb-6"></p>

                <!-- Size options simulation -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilihan Ukuran:</label>
                    <div class="flex space-x-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="qvSize" value="S" class="peer hidden" checked>
                            <span class="w-8 h-8 rounded-lg border border-blush-200 flex items-center justify-center text-xs font-bold peer-checked:bg-blush-600 peer-checked:text-white transition-colors">S</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="qvSize" value="M" class="peer hidden">
                            <span class="w-8 h-8 rounded-lg border border-blush-200 flex items-center justify-center text-xs font-bold peer-checked:bg-blush-600 peer-checked:text-white transition-colors">M</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="qvSize" value="L" class="peer hidden">
                            <span class="w-8 h-8 rounded-lg border border-blush-200 flex items-center justify-center text-xs font-bold peer-checked:bg-blush-600 peer-checked:text-white transition-colors">L</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="qvSize" value="XL" class="peer hidden">
                            <span class="w-8 h-8 rounded-lg border border-blush-200 flex items-center justify-center text-xs font-bold peer-checked:bg-blush-600 peer-checked:text-white transition-colors">XL</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button type="button" id="qvAddToCartBtn" class="w-full btn-add-cart py-3 px-4 rounded-xl text-white text-xs font-bold flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Tambahkan ke Keranjang</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="cartToast" class="fixed bottom-6 right-6 z-50 hidden bg-blush-900 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center space-x-3 transform translate-y-6 transition-all duration-300 border border-blush-700">
    <div class="w-7 h-7 rounded-full bg-blush-500 flex items-center justify-center text-white text-xs font-bold">
        ✓
    </div>
    <div>
        <p class="text-xs font-bold" id="toastMessage">Busana berhasil ditambahkan!</p>
        <p class="text-[10px] text-blush-200">Buka keranjang untuk menyelesaikan pesanan.</p>
    </div>
    <button type="button" onclick="openCartDrawer()" class="ml-2 text-xs font-bold text-blush-300 hover:text-white underline">
        Lihat
    </button>
</div>

<!-- Floating Cart Action Button for Mobile -->
<div class="fixed bottom-6 left-6 z-40 md:hidden">
    <button type="button" onclick="openCartDrawer()" class="w-14 h-14 rounded-full btn-add-cart text-white shadow-xl flex items-center justify-center relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <span id="mobileCartCount" class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-white text-blush-800 text-xs font-bold flex items-center justify-center shadow-md">0</span>
    </button>
</div>

<!-- Front-End JavaScript E-Commerce Shopping Cart System -->
<script>
    // State management for Cart
    const CART_STORAGE_KEY = 'blush_boutique_cart';
    let cart = [];

    // Initialize cart from localStorage
    try {
        const stored = localStorage.getItem(CART_STORAGE_KEY);
        if (stored) {
            cart = JSON.parse(stored);
        }
    } catch (e) {
        cart = [];
    }

    function saveCart() {
        try {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {}
        updateCartUI();
    }

    function addToCart(product, size = 'All Size') {
        const existingIndex = cart.findIndex(item => item.id === product.id && item.size === size);
        if (existingIndex > -1) {
            cart[existingIndex].quantity += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                sku: product.sku,
                price: parseFloat(product.price) || 0,
                formatted_price: product.formatted_price,
                image: product.image,
                size: size,
                quantity: 1,
            });
        }
        saveCart();
        showToast(`🌸 ${product.name} dimasukkan ke keranjang!`);
    }

    function updateItemQty(index, delta) {
        if (cart[index]) {
            cart[index].quantity += delta;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            saveCart();
        }
    }

    function removeCartItem(index) {
        if (cart[index]) {
            cart.splice(index, 1);
            saveCart();
        }
    }

    function clearCart() {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?')) {
            cart = [];
            saveCart();
        }
    }

    function updateCartUI() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const grandTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        // Update badge counters
        const headerCount = document.getElementById('headerCartCount');
        if (headerCount) headerCount.innerText = totalItems;
        const badgeBtn = document.getElementById('badgeCartBtn');
        if (badgeBtn) badgeBtn.innerText = totalItems;
        const mobileCount = document.getElementById('mobileCartCount');
        if (mobileCount) mobileCount.innerText = totalItems;
        const totalItemsCountElem = document.getElementById('cartTotalItemsCount');
        if (totalItemsCountElem) totalItemsCountElem.innerText = `${totalItems} pcs`;

        // Update Grand Total text
        const grandTotalElem = document.getElementById('cartGrandTotalText');
        if (grandTotalElem) {
            grandTotalElem.innerText = 'Rp ' + Number(grandTotal).toLocaleString('id-ID');
        }

        // Render Cart Items List
        const listElem = document.getElementById('cartItemsList');
        if (!listElem) return;

        if (cart.length === 0) {
            listElem.innerHTML = `
                <div class="text-center py-12 text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-blush-50 text-blush-300 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="font-serif-luxury text-base font-bold text-gray-700 mb-1">Keranjang Masih Kosong</p>
                    <p class="text-xs text-gray-400">Pilih busana idaman Anda dan klik 'Tambah ke Keranjang'.</p>
                </div>
            `;
            return;
        }

        let html = '';
        cart.forEach((item, idx) => {
            const subtotal = item.price * item.quantity;
            html += `
                <div class="p-3 bg-blush-50/40 rounded-2xl border border-blush-100 flex items-center space-x-3">
                    <div class="w-14 h-18 rounded-xl overflow-hidden bg-blush-100 flex-shrink-0" style="aspect-ratio: 3/4;">
                        <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 truncate">${item.name}</h4>
                        <span class="text-[10px] text-gray-500 font-mono block">SKU: ${item.sku} • Size: ${item.size}</span>
                        <span class="text-xs font-bold text-blush-700">Rp ${Number(subtotal).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex items-center space-x-1.5 flex-shrink-0">
                        <button type="button" onclick="updateItemQty(${idx}, -1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">-</button>
                        <span class="text-xs font-bold text-gray-800 w-5 text-center">${item.quantity}</span>
                        <button type="button" onclick="updateItemQty(${idx}, 1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">+</button>
                        <button type="button" onclick="removeCartItem(${idx})" class="p-1 text-gray-400 hover:text-rose-500 ml-1" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            `;
        });
        listElem.innerHTML = html;
    }

    // Drawer Visibility
    function openCartDrawer() {
        const backdrop = document.getElementById('cartDrawerBackdrop');
        const panel = document.getElementById('cartDrawerPanel');
        if (!backdrop || !panel) return;

        backdrop.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeCartDrawer() {
        const backdrop = document.getElementById('cartDrawerBackdrop');
        const panel = document.getElementById('cartDrawerPanel');
        if (!backdrop || !panel) return;

        backdrop.classList.add('opacity-0');
        panel.classList.add('translate-x-full');
        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 350);
    }

    // Navigate to COD Checkout Page
    function proceedToCheckout() {
        if (!cart || cart.length === 0) {
            alert('Keranjang belanja Anda masih kosong. Silakan pilih busana terlebih dahulu.');
            return;
        }
        window.location.href = "{{ route('checkout.show') }}";
    }

    // Quick View Modal
    let currentQvProduct = null;
    function openQuickView(product) {
        currentQvProduct = product;
        document.getElementById('qvImage').src = product.image;
        document.getElementById('qvTitle').innerText = product.name;
        document.getElementById('qvCategory').innerText = product.category;
        document.getElementById('qvSku').innerText = 'SKU: ' + product.sku;
        document.getElementById('qvPrice').innerText = product.formatted_price;
        document.getElementById('qvDescription').innerText = product.description || 'Busana elegan dengan rancangan eksklusif.';

        const modal = document.getElementById('quickViewModal');
        const card = document.getElementById('quickViewCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
        }, 10);

        // Bind add to cart in quick view
        document.getElementById('qvAddToCartBtn').onclick = () => {
            const checkedSize = document.querySelector('input[name="qvSize"]:checked');
            const size = checkedSize ? checkedSize.value : 'M';
            addToCart(product, size);
            closeQuickView();
        };
    }

    function closeQuickView() {
        const modal = document.getElementById('quickViewModal');
        const card = document.getElementById('quickViewCard');
        modal.classList.add('opacity-0');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Toast Notification
    let toastTimeout = null;
    function showToast(text) {
        const toast = document.getElementById('cartToast');
        document.getElementById('toastMessage').innerText = text;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.remove('translate-y-6');
        }, 10);

        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.add('translate-y-6');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, 3000);
    }

    // Client-side quick filter on keyup
    document.getElementById('searchKeyword').addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-item-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            if (keyword === '' || name.includes(keyword)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Close drawers on clicking backdrop
    document.getElementById('cartDrawerBackdrop').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCartDrawer();
        }
    });

    document.getElementById('quickViewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQuickView();
        }
    });

    // Initialize UI on DOM load
    window.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
    });
</script>
@endsection
