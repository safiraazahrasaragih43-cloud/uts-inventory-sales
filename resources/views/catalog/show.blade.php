@extends('layouts.public', ['title' => $product->name])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-gray-400 mb-8">
        <a href="{{ route('catalog.index') }}" class="hover:text-blush-600 transition-colors">Koleksi Busana</a>
        <span>/</span>
        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-blush-600 transition-colors">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-gray-700 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-blush-100 shadow-xl shadow-blush-500/5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <!-- Product Visual (3:4 Ratio) -->
            <div class="relative bg-blush-50 rounded-3xl overflow-hidden border border-blush-200 shadow-md w-full" style="aspect-ratio: 3/4;">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80';">
                
                <div class="absolute bottom-4 left-4 z-10">
                    <span class="text-xs font-semibold text-blush-900 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-blush-200 shadow-sm flex items-center space-x-1">
                        <span>🌸</span>
                        <span>{{ $product->category->name }}</span>
                    </span>
                </div>

                <div class="absolute top-4 right-4 z-10">
                    @if($product->stock > 5)
                        <span class="px-3.5 py-1.5 text-xs font-bold rounded-full bg-emerald-500 text-white shadow-sm flex items-center space-x-1">
                            <span>Ready Stock ({{ $product->stock }} pcs)</span>
                        </span>
                    @elseif($product->stock > 0)
                        <span class="px-3.5 py-1.5 text-xs font-bold rounded-full bg-amber-500 text-white shadow-sm">
                            Sisa {{ $product->stock }} pcs
                        </span>
                    @else
                        <span class="px-3.5 py-1.5 text-xs font-bold rounded-full bg-rose-600 text-white shadow-sm">
                            Stok Habis
                        </span>
                    @endif
                </div>
            </div>

            <!-- Product Info & WhatsApp Order Action -->
            <div>
                <div class="flex items-center space-x-2 text-xs text-gray-500 mb-2">
                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">SKU: {{ $product->sku }}</span>
                    <span>•</span>
                    <span class="text-blush-600 font-semibold">{{ $product->category->name }}</span>
                </div>

                <h1 class="font-serif-luxury text-3xl sm:text-4xl font-bold text-blush-900 mb-4 leading-tight">
                    {{ $product->name }}
                </h1>

                <div class="mb-6 pb-6 border-b border-blush-100">
                    <span class="text-xs text-gray-400 block mb-1">Harga Resmi:</span>
                    <span class="text-3xl font-bold text-blush-800">
                        {{ $product->formatted_selling_price }}
                    </span>
                </div>

                <div class="mb-8">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Deskripsi Produk:</h3>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $product->description ?: 'Busana anggun dengan jahitan rapi, bahan premium adem, dan dirancang khusus untuk kenyamanan Anda.' }}
                    </p>
                </div>

                <!-- Highlight Box -->
                <div class="p-4 rounded-2xl bg-blush-50/60 border border-blush-200 mb-8 space-y-2 text-xs text-blush-900">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">🌸</span>
                        <span>Bahan berkualitas lembut, dingin, dan tidak menerawang.</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-base">🚚</span>
                        <span>Pengiriman amanah ke seluruh Indonesia dari Bandung.</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-base">💬</span>
                        <span>Pemesanan dilayani langsung oleh Admin CS BlushBoutique.</span>
                    </div>
                </div>

                <!-- WhatsApp Direct Order Button -->
                <div class="space-y-3">
                    <a href="{{ $waUrl }}" target="_blank" class="w-full py-4 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center space-x-2.5 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.302c-.087.086-.179.18-.077.355.101.174.449.741.964 1.2.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.505.549-.679.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                        <span>Pesan via WhatsApp Sekarang</span>
                    </a>

                    <a href="{{ route('catalog.index') }}" class="block text-center text-xs font-semibold text-gray-500 hover:text-blush-600 transition-colors py-2">
                        ← Kembali ke Koleksi Pakaian
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
