@extends('layouts.public', ['title' => 'Formulir Pengiriman COD (Bayar di Tempat)'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

    <!-- Checkout Progress Step Indicator -->
    <div class="mb-10 max-w-3xl mx-auto">
        <div class="flex items-center justify-between text-xs sm:text-sm font-semibold">
            <div class="flex items-center space-x-2 text-emerald-600">
                <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-xs">✓</span>
                <span class="hidden sm:inline">Pilih Busana</span>
            </div>
            <div class="w-12 sm:w-24 h-0.5 bg-blush-300"></div>
            <div class="flex items-center space-x-2 text-blush-700">
                <span class="w-7 h-7 rounded-full bg-blush-600 text-white flex items-center justify-center font-bold text-xs ring-4 ring-blush-200">2</span>
                <span class="font-bold">Alamat & Pengiriman COD</span>
            </div>
            <div class="w-12 sm:w-24 h-0.5 bg-gray-200"></div>
            <div class="flex items-center space-x-2 text-gray-400">
                <span class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center font-bold text-xs">3</span>
                <span class="hidden sm:inline">Faktur & Pengiriman</span>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi / Error -->
    @if(session('error'))
        <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start space-x-3 text-sm">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <span class="font-bold">Terjadi Kesalahan:</span> {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <p class="font-bold mb-1">Mohon lengkapi formulir pengiriman berikut:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutCodForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Data Penerima & Metode COD (8 Cols) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                
                <!-- Card 1: Data Pengiriman -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200 shadow-xl shadow-blush-500/5">
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-blush-100">
                        <div class="w-10 h-10 rounded-2xl bg-blush-100 text-blush-700 flex items-center justify-center font-bold">
                            📍
                        </div>
                        <div>
                            <h2 class="font-serif-luxury text-xl sm:text-2xl font-bold text-gray-900">Alamat Pengiriman Paket</h2>
                            <p class="text-xs text-gray-500">Pastikan data nama, telepon, dan alamat ditulis lengkap untuk kurir COD.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nama Lengkap Penerima <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="customer_name"
                                   id="customer_name"
                                   required
                                   value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}"
                                   placeholder="Contoh: Safira Maharani"
                                   class="w-full px-4 py-3 rounded-2xl bg-blush-50/40 border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 focus:bg-white text-sm text-gray-800 transition-all">
                        </div>

                        <!-- Nomor Telepon / HP -->
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nomor Telepon / WhatsApp Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   required
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-3 rounded-2xl bg-blush-50/40 border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 focus:bg-white text-sm text-gray-800 transition-all">
                            <p class="text-[11px] text-gray-400 mt-1">Kurir akan menghubungi nomor ini saat melakukan pengantaran paket pesanan Anda.</p>
                        </div>

                        <!-- Alamat Lengkap Pengiriman -->
                        <div>
                            <label for="address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Alamat Lengkap Pengiriman COD <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="address"
                                      id="address"
                                      rows="3"
                                      required
                                      placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos..."
                                      class="w-full px-4 py-3 rounded-2xl bg-blush-50/40 border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 focus:bg-white text-sm text-gray-800 transition-all leading-relaxed">{{ old('address') }}</textarea>
                            <p class="text-[11px] text-gray-400 mt-1">Sertakan patokan lokasi terdekat (misal: samping masjid, pagar putih) agar kurir cepat menemukan rumah.</p>
                        </div>

                        <!-- Catatan Khusus / Patokan Alamat -->
                        <div>
                            <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Catatan Pesanan / Instruksi Kurir <span class="text-gray-400 font-normal lowercase">(opsional)</span>
                            </label>
                            <input type="text"
                                   name="notes"
                                   id="notes"
                                   value="{{ old('notes') }}"
                                   placeholder="Contoh: Mohon diantar sebelum jam 5 sore atau titipkan ke tetangga jika tidak di rumah"
                                   class="w-full px-4 py-3 rounded-2xl bg-blush-50/40 border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 focus:bg-white text-sm text-gray-800 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Metode Pembayaran COD -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200 shadow-xl shadow-blush-500/5">
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-blush-100">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            💵
                        </div>
                        <div>
                            <h2 class="font-serif-luxury text-xl sm:text-2xl font-bold text-gray-900">Metode Pembayaran</h2>
                            <p class="text-xs text-gray-500">Seluruh pemesanan online diproses dengan sistem Bayar di Tempat (COD).</p>
                        </div>
                    </div>

                    <!-- Pilihan Pembayaran COD Aktif -->
                    <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-50/60 to-blush-50/60 border-2 border-emerald-400/80 flex items-start space-x-4">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-grow">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                <span class="font-serif-luxury font-bold text-base text-gray-900">COD (Cash on Delivery / Bayar Tunai di Tempat)</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white tracking-wide uppercase">Metode Resmi</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Pesanan Anda langsung diproses dan dikirimkan oleh kurir kami. <strong>Tidak perlu transfer sekarang</strong> — cukup serahkan uang pas secara tunai kepada kurir saat paket busana tiba di depan pintu rumah Anda.
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2 text-[11px] text-gray-500 font-medium">
                                <span class="inline-flex items-center space-x-1 bg-white px-2.5 py-1 rounded-lg border border-emerald-200">
                                    <span>📦</span> <span>Barang Dikemas Rapi</span>
                                </span>
                                <span class="inline-flex items-center space-x-1 bg-white px-2.5 py-1 rounded-lg border border-emerald-200">
                                    <span>🛡️</span> <span>Bebas Risiko Penipuan</span>
                                </span>
                                <span class="inline-flex items-center space-x-1 bg-white px-2.5 py-1 rounded-lg border border-emerald-200">
                                    <span>⚡</span> <span>Proses Instan di Website</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Ringkasan Pesanan & Tombol Submit (5 Cols) -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-blush-200 shadow-xl shadow-blush-500/5 sticky top-28">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-blush-100 mb-5">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-xl bg-blush-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                🛍️
                            </div>
                            <h3 class="font-serif-luxury text-lg font-bold text-gray-900">Ringkasan Pesanan</h3>
                        </div>
                        <span id="orderItemCountBadge" class="text-xs font-semibold px-2.5 py-1 bg-blush-50 text-blush-700 rounded-full border border-blush-200">
                            {{ $directProduct ? '1 Busana' : '0 Busana' }}
                        </span>
                    </div>

                    <!-- Direct Order vs Cart Items Container -->
                    <div id="checkoutItemsList" class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                        @if($directProduct)
                            <!-- Mode Pesan Langsung dari Tombol 'Pesan Sekarang' di Kartu Katalog -->
                            <div class="p-3.5 bg-blush-50/50 rounded-2xl border border-blush-200 flex space-x-3.5" id="directItemRow">
                                <div class="w-16 h-20 rounded-xl overflow-hidden bg-blush-100 flex-shrink-0" style="aspect-ratio: 3/4;">
                                    <img src="{{ $directProduct->image_url ?: 'https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $directProduct->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <span class="text-[10px] uppercase font-bold text-blush-600 tracking-wider block">
                                        {{ $directProduct->category->name }}
                                    </span>
                                    <h4 class="text-xs font-bold text-gray-900 truncate leading-snug" title="{{ $directProduct->name }}">
                                        {{ $directProduct->name }}
                                    </h4>
                                    <p class="text-[10px] font-mono text-gray-500 mt-0.5">SKU: {{ $directProduct->sku }}</p>
                                    
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs font-bold text-blush-800" id="directItemUnitPrice">
                                            {{ $directProduct->formatted_selling_price }}
                                        </span>
                                        <div class="flex items-center space-x-1.5">
                                            <button type="button" onclick="adjustDirectQty(-1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">-</button>
                                            <span class="text-xs font-bold text-gray-800 w-5 text-center" id="directQtyDisplay">1</span>
                                            <button type="button" onclick="adjustDirectQty(1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan Ukuran untuk Pesan Langsung -->
                            <div class="p-3 bg-blush-50/30 rounded-2xl border border-blush-100">
                                <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Pilih Ukuran Busana:</label>
                                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="direct_size_radio" value="S" onchange="updateDirectSize(this.value)" class="peer hidden">
                                        <span class="block py-1.5 rounded-xl border border-blush-200 peer-checked:bg-blush-600 peer-checked:text-white font-bold transition-all">S</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="direct_size_radio" value="M" onchange="updateDirectSize(this.value)" class="peer hidden" checked>
                                        <span class="block py-1.5 rounded-xl border border-blush-200 peer-checked:bg-blush-600 peer-checked:text-white font-bold transition-all">M</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="direct_size_radio" value="L" onchange="updateDirectSize(this.value)" class="peer hidden">
                                        <span class="block py-1.5 rounded-xl border border-blush-200 peer-checked:bg-blush-600 peer-checked:text-white font-bold transition-all">L</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="direct_size_radio" value="XL" onchange="updateDirectSize(this.value)" class="peer hidden">
                                        <span class="block py-1.5 rounded-xl border border-blush-200 peer-checked:bg-blush-600 peer-checked:text-white font-bold transition-all">XL</span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder untuk data keranjang localStorage -->
                            <div id="cartLoadingPlaceholder" class="text-center py-8 text-gray-400">
                                <svg class="animate-spin h-6 w-6 text-blush-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span class="text-xs">Memuat rincian keranjang...</span>
                            </div>
                        @endif
                    </div>

                    <!-- Hidden JSON data input submitted to Controller -->
                    <input type="hidden" name="items_json" id="itemsJsonInput" value="">
                    @if($directProduct)
                        <input type="hidden" name="direct_product_id" id="directProductId" value="{{ $directProduct->id }}">
                        <input type="hidden" name="direct_quantity" id="directQuantity" value="1">
                        <input type="hidden" name="direct_size" id="directSize" value="M">
                    @endif

                    <!-- Kalkulasi Biaya Pesanan -->
                    <div class="mt-5 pt-4 border-t border-blush-100 space-y-2 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Produk</span>
                            <span class="font-bold text-gray-900" id="summarySubtotalText">
                                {{ $directProduct ? $directProduct->formatted_selling_price : 'Rp 0' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600 items-center">
                            <span>Biaya Pengiriman Kurir COD</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">GRATIS ONGKIR</span>
                        </div>
                        <div class="flex justify-between text-gray-600 items-center">
                            <span>Biaya Admin & Penanganan COD</span>
                            <span class="font-bold text-emerald-600">Rp 0 (Gratis)</span>
                        </div>
                        <div class="pt-3 border-t border-blush-200 flex justify-between items-baseline">
                            <div>
                                <span class="text-sm font-bold text-gray-900 block font-serif-luxury">Total Tagihan COD:</span>
                                <span class="text-[10px] text-gray-400">Bayar pas saat barang tiba</span>
                            </div>
                            <span class="font-serif-luxury text-xl sm:text-2xl font-bold text-blush-800" id="summaryGrandTotalText">
                                {{ $directProduct ? $directProduct->formatted_selling_price : 'Rp 0' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Pesan Sekarang COD -->
                    <div class="mt-6 space-y-3">
                        <button type="submit"
                                id="btnSubmitCheckout"
                                class="w-full py-4 px-6 rounded-2xl blush-btn text-white font-bold text-sm tracking-wide shadow-lg flex items-center justify-center space-x-2 cursor-pointer transition-all">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Pesan Sekarang (Konfirmasi COD)</span>
                        </button>

                        <a href="{{ route('catalog.index') }}" class="block text-center text-xs font-semibold text-gray-400 hover:text-blush-600 py-1 transition-colors">
                            ← Kembali Memilih Busana Lain
                        </a>
                    </div>

                    <!-- Reassurance Footer -->
                    <div class="mt-5 p-3 rounded-xl bg-blush-50/50 border border-blush-100 text-[11px] text-gray-500 space-y-1">
                        <div class="flex items-center space-x-1.5 text-blush-800 font-semibold">
                            <span>✨</span> <span>100% Aman & Tanpa Transfer</span>
                        </div>
                        <p class="leading-relaxed">Seluruh proses pesanan tersimpan resmi di sistem BlushBoutique tanpa redirect ke WhatsApp.</p>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

<script>
    const isDirectOrder = {{ $directProduct ? 'true' : 'false' }};
    const CART_STORAGE_KEY = 'blush_boutique_cart';

    // Handler untuk Pemesanan Langsung (Direct Order)
    let directProductPrice = {{ $directProduct ? (float) $directProduct->selling_price : 0 }};
    let directProductStock = {{ $directProduct ? (int) $directProduct->stock : 0 }};
    let directQty = 1;
    let directSize = 'M';

    function adjustDirectQty(delta) {
        const newQty = directQty + delta;
        if (newQty < 1) return;
        if (directProductStock > 0 && newQty > directProductStock) {
            alert(`Maksimal pembelian untuk produk ini adalah ${directProductStock} pcs (sesuai sisa stok).`);
            return;
        }
        directQty = newQty;
        document.getElementById('directQtyDisplay').innerText = directQty;
        document.getElementById('directQuantity').value = directQty;

        const subtotal = directProductPrice * directQty;
        const formatted = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
        document.getElementById('summarySubtotalText').innerText = formatted;
        document.getElementById('summaryGrandTotalText').innerText = formatted;

        // Synchronize hidden items_json
        syncDirectItemsJson();
    }

    function updateDirectSize(size) {
        directSize = size;
        const input = document.getElementById('directSize');
        if (input) input.value = size;
        syncDirectItemsJson();
    }

    function syncDirectItemsJson() {
        if (!isDirectOrder) return;
        const productId = document.getElementById('directProductId').value;
        const items = [{
            product_id: parseInt(productId),
            quantity: directQty,
            size: directSize,
        }];
        document.getElementById('itemsJsonInput').value = JSON.stringify(items);
    }

    // Handler untuk Pemesanan dari Keranjang (Cart Order)
    let cartItems = [];

    function loadCartForCheckout() {
        if (isDirectOrder) {
            syncDirectItemsJson();
            return;
        }

        try {
            const raw = localStorage.getItem(CART_STORAGE_KEY);
            cartItems = raw ? JSON.parse(raw) : [];
        } catch (e) {
            cartItems = [];
        }

        renderCartInCheckout();
    }

    function renderCartInCheckout() {
        const container = document.getElementById('checkoutItemsList');
        const countBadge = document.getElementById('orderItemCountBadge');
        const subtotalText = document.getElementById('summarySubtotalText');
        const grandTotalText = document.getElementById('summaryGrandTotalText');
        const submitBtn = document.getElementById('btnSubmitCheckout');

        if (!container) return;

        if (!cartItems || cartItems.length === 0) {
            container.innerHTML = `
                <div class="text-center py-10 px-4 bg-blush-50/50 rounded-2xl border border-blush-200">
                    <div class="w-12 h-12 rounded-full bg-blush-100 text-blush-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h4 class="font-serif-luxury font-bold text-gray-800 mb-1">Keranjang Masih Kosong</h4>
                    <p class="text-xs text-gray-500 mb-4">Anda belum memasukkan produk busana ke keranjang belanja.</p>
                    <a href="{{ route('catalog.index') }}" class="inline-block py-2.5 px-4 rounded-xl blush-btn text-white text-xs font-bold shadow-md">
                        Pilih Busana Sekarang
                    </a>
                </div>
            `;
            countBadge.innerText = '0 Busana';
            subtotalText.innerText = 'Rp 0';
            grandTotalText.innerText = 'Rp 0';
            document.getElementById('itemsJsonInput').value = '[]';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        let totalAmount = 0;
        let totalCount = 0;
        let html = '';
        const itemsPayload = [];

        cartItems.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            totalAmount += subtotal;
            totalCount += item.quantity;

            itemsPayload.push({
                product_id: parseInt(item.id),
                quantity: item.quantity,
                size: item.size || 'All Size',
            });

            html += `
                <div class="p-3 bg-blush-50/40 rounded-2xl border border-blush-200 flex items-center space-x-3">
                    <div class="w-14 h-18 rounded-xl overflow-hidden bg-blush-100 flex-shrink-0" style="aspect-ratio: 3/4;">
                        <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 truncate">${item.name}</h4>
                        <div class="flex items-center space-x-2 text-[10px] text-gray-500 font-mono mt-0.5">
                            <span>SKU: ${item.sku}</span>
                            <span>•</span>
                            <span class="text-blush-700 font-bold">Size ${item.size || 'All Size'}</span>
                        </div>
                        <div class="text-xs font-bold text-blush-800 mt-1">
                            Rp ${Number(subtotal).toLocaleString('id-ID')}
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5 flex-shrink-0">
                        <button type="button" onclick="changeCheckoutCartQty(${index}, -1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">-</button>
                        <span class="text-xs font-bold text-gray-800 w-5 text-center">${item.quantity}</span>
                        <button type="button" onclick="changeCheckoutCartQty(${index}, 1)" class="w-6 h-6 rounded-lg bg-white border border-blush-200 text-blush-800 text-xs font-bold flex items-center justify-center hover:bg-blush-100">+</button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        countBadge.innerText = `${totalCount} Pcs (${cartItems.length} Produk)`;
        const formattedTotal = 'Rp ' + Number(totalAmount).toLocaleString('id-ID');
        subtotalText.innerText = formattedTotal;
        grandTotalText.innerText = formattedTotal;

        // Set hidden items_json payload
        document.getElementById('itemsJsonInput').value = JSON.stringify(itemsPayload);
    }

    function changeCheckoutCartQty(index, delta) {
        if (!cartItems[index]) return;
        cartItems[index].quantity += delta;
        if (cartItems[index].quantity <= 0) {
            cartItems.splice(index, 1);
        }
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems));
        renderCartInCheckout();
    }

    // Submit Validation Guard
    document.getElementById('checkoutCodForm').addEventListener('submit', function(e) {
        const payload = document.getElementById('itemsJsonInput').value;
        if (!payload || payload === '[]') {
            e.preventDefault();
            alert('Tidak ada busana dalam pesanan Anda. Silakan pilih pakaian terlebih dahulu dari katalog.');
            return false;
        }

        const name = document.getElementById('customer_name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const address = document.getElementById('address').value.trim();

        if (!name || !phone || !address) {
            e.preventDefault();
            alert('Mohon isi nama lengkap, nomor telepon, dan alamat pengiriman dengan lengkap.');
            return false;
        }

        // Tampilkan indikator proses submit
        const submitBtn = document.getElementById('btnSubmitCheckout');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            <span>Memproses Pesanan COD...</span>
        `;
    });

    window.addEventListener('DOMContentLoaded', () => {
        loadCartForCheckout();
    });
</script>
@endsection
