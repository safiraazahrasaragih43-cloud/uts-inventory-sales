@extends('layouts.public', ['title' => 'Pesanan COD Berhasil - ' . $sale->invoice_number])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

    <!-- Checkout Progress Step Indicator -->
    <div class="mb-10 max-w-2xl mx-auto">
        <div class="flex items-center justify-between text-xs sm:text-sm font-semibold">
            <div class="flex items-center space-x-2 text-emerald-600">
                <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-xs">✓</span>
                <span class="hidden sm:inline">Pilih Busana</span>
            </div>
            <div class="w-12 sm:w-24 h-0.5 bg-emerald-400"></div>
            <div class="flex items-center space-x-2 text-emerald-600">
                <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-xs">✓</span>
                <span class="hidden sm:inline">Alamat COD</span>
            </div>
            <div class="w-12 sm:w-24 h-0.5 bg-blush-400"></div>
            <div class="flex items-center space-x-2 text-blush-700">
                <span class="w-7 h-7 rounded-full bg-blush-600 text-white flex items-center justify-center font-bold text-xs ring-4 ring-blush-200">3</span>
                <span class="font-bold">Pesanan Sukses</span>
            </div>
        </div>
    </div>

    <!-- Success Celebration Header -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mx-auto mb-4 shadow-xl shadow-emerald-500/25 animate-bounce">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="inline-block py-1 px-3.5 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm mb-2">
            ✨ Pesanan Berhasil Dikonfirmasi
        </span>
        <h1 class="font-serif-luxury text-3xl sm:text-4xl font-bold text-blush-900 mb-2">
            Terima Kasih Atas Pesanan Anda!
        </h1>
        <p class="text-sm text-gray-600 max-w-lg mx-auto">
            Pesanan dengan metode pembayaran <strong>COD (Bayar di Tempat)</strong> telah resmi tercatat di sistem butik kami dan siap dikemas.
        </p>
    </div>

    <!-- Main Invoice Receipt Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-blush-200 shadow-xl shadow-blush-500/5 print:border-none print:shadow-none mb-8">
        
        <!-- Invoice Header Meta -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-6 border-b border-blush-100 gap-4">
            <div>
                <span class="text-xs uppercase font-bold tracking-wider text-gray-400 block mb-1">Nomor Faktur Pesanan</span>
                <span class="font-mono text-xl sm:text-2xl font-bold text-blush-900 tracking-wide">
                    {{ $sale->invoice_number }}
                </span>
            </div>
            <div class="sm:text-right">
                <span class="text-xs uppercase font-bold tracking-wider text-gray-400 block mb-1">Waktu Transaksi</span>
                <span class="text-sm font-semibold text-gray-700">
                    {{ $sale->sale_date ? $sale->sale_date->format('d F Y, H:i') : date('d F Y, H:i') }} WIB
                </span>
            </div>
        </div>

        <!-- Detail Pengiriman & Pembayaran Badges -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6 p-5 rounded-2xl bg-blush-50/40 border border-blush-100 text-xs text-gray-700">
            <div>
                <h4 class="font-serif-luxury font-bold text-sm text-blush-900 mb-2 flex items-center space-x-1.5">
                    <span>📍</span> <span>Tujuan Pengiriman COD</span>
                </h4>
                <p class="font-bold text-gray-900 text-sm mb-0.5">{{ $sale->customer_name }}</p>
                <p class="text-gray-600 font-mono mb-1.5">📞 {{ $sale->customer->phone ?? '-' }}</p>
                <p class="text-gray-600 leading-relaxed bg-white p-2.5 rounded-xl border border-blush-100">
                    {{ $sale->notes }}
                </p>
            </div>

            <div>
                <h4 class="font-serif-luxury font-bold text-sm text-blush-900 mb-2 flex items-center space-x-1.5">
                    <span>💵</span> <span>Instruksi Pembayaran</span>
                </h4>
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>{{ $sale->payment_method }}</span>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Siapkan uang tunai pas sebesar <strong>{{ $sale->formatted_total_amount }}</strong> saat kurir mengantarkan paket ke alamat Anda. Tidak perlu transfer sekarang.
                </p>
            </div>
        </div>

        <!-- Rincian Produk yang Dipesan -->
        <div class="mb-6">
            <h4 class="font-serif-luxury font-bold text-base text-gray-900 mb-4 flex items-center space-x-2">
                <span>👗</span> <span>Daftar Busana dalam Pesanan</span>
            </h4>

            <div class="divide-y divide-blush-100 border border-blush-100 rounded-2xl overflow-hidden">
                @foreach($sale->items as $item)
                    <div class="p-4 flex items-center justify-between gap-4 bg-white hover:bg-blush-50/20 transition-colors">
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-14 h-18 rounded-xl overflow-hidden bg-blush-100 flex-shrink-0" style="aspect-ratio: 3/4;">
                                <img src="{{ $item->product->image_url ?: 'https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=400&q=80' }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] uppercase font-bold text-blush-600 tracking-wider block">
                                    {{ $item->product->category->name ?? 'Busana' }}
                                </span>
                                <h5 class="text-sm font-bold text-gray-900 truncate" title="{{ $item->product->name }}">
                                    {{ $item->product->name }}
                                </h5>
                                <div class="flex items-center space-x-2 text-xs text-gray-500 font-mono mt-0.5">
                                    <span>SKU: {{ $item->product->sku }}</span>
                                    <span>•</span>
                                    <span>{{ $item->quantity }} pcs &times; {{ $item->formatted_unit_price }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-bold text-blush-900 font-serif-luxury">
                                {{ $item->formatted_subtotal }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ringkasan Biaya Pembayaran COD -->
        <div class="border-t border-blush-200 pt-5 space-y-2 text-xs">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal Pakaian</span>
                <span class="font-bold text-gray-900">{{ $sale->formatted_total_amount }}</span>
            </div>
            <div class="flex justify-between text-gray-600 items-center">
                <span>Biaya Pengiriman (Ekspedisi Kurir COD)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">GRATIS ONGKIR</span>
            </div>
            <div class="flex justify-between text-gray-600 items-center">
                <span>Biaya Penanganan / Layanan COD</span>
                <span class="font-bold text-emerald-600">Rp 0 (Bebas Biaya)</span>
            </div>
            <div class="pt-3 border-t-2 border-blush-200 flex justify-between items-baseline">
                <div>
                    <span class="text-base font-bold text-gray-900 block font-serif-luxury">TOTAL DIBAYAR KEPADA KURIR:</span>
                    <span class="text-[11px] text-gray-500">Bayar tunai pas saat kurir tiba</span>
                </div>
                <span class="font-serif-luxury text-2xl sm:text-3xl font-bold text-blush-800">
                    {{ $sale->formatted_total_amount }}
                </span>
            </div>
        </div>

    </div>

    <!-- Informasi Alur Selanjutnya -->
    <div class="bg-gradient-to-r from-blush-50 to-pastel-rose rounded-3xl p-6 sm:p-7 border border-blush-200 mb-8 space-y-3">
        <h3 class="font-serif-luxury text-base font-bold text-blush-900 flex items-center space-x-2">
            <span>📦</span> <span>Langkah Selanjutnya Pengiriman Busana Anda:</span>
        </h3>
        <ol class="list-decimal list-inside space-y-2 text-xs sm:text-sm text-gray-700 leading-relaxed pl-1">
            <li><strong>Pengemasan Eksklusif:</strong> Tim kami segera memeriksa kualitas kain dan mengemas pesanan dengan rapi dan aman.</li>
            <li><strong>Penyerahan ke Kurir COD:</strong> Paket diserahkan ke kurir ekspedisi untuk pengiriman ke alamat tujuan Anda.</li>
            <li><strong>Penerimaan & Pembayaran:</strong> Saat kurir tiba di alamat Anda, serahkan uang pas <strong>{{ $sale->formatted_total_amount }}</strong> dan nikmati koleksi pakaian cantik BlushBoutique!</li>
        </ol>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl blush-btn text-white text-xs sm:text-sm font-bold shadow-md flex items-center justify-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Lanjut Belanja Koleksi Lain</span>
        </a>
        <button type="button" onclick="window.print()" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-white hover:bg-blush-50 text-blush-800 border border-blush-300 text-xs sm:text-sm font-semibold shadow-sm transition-colors flex items-center justify-center space-x-2">
            <svg class="w-4 h-4 text-blush-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Bukti Faktur</span>
        </button>
    </div>

</div>

<!-- Auto Clear Shopping Cart after successful order submission -->
<script>
    try {
        localStorage.removeItem('blush_boutique_cart');
    } catch (e) {}
</script>
@endsection
