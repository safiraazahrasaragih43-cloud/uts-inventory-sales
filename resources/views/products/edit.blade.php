@extends('layouts.app')

@section('title', 'Ubah Produk: ' . $product->name)
@section('page_title', 'Perbarui Data Produk Busana')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Perbarui: {{ $product->name }}</h3>
                <p class="text-xs text-gray-500">Edit SKU, harga jual, harga modal, atau batas minimum stok.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Image Upload with Live Preview & Existing Image -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Foto Pakaian / Busana</label>
                    <div class="flex items-center space-x-5">
                        <div class="w-24 h-24 rounded-2xl bg-blush-50 border-2 border-dashed border-blush-300 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm relative">
                            @if($product->image_url)
                                <img id="imagePreview" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                <div id="imagePlaceholder" class="text-center text-blush-400 p-2 hidden">
                                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] block">Belum ada foto</span>
                                </div>
                            @else
                                <img id="imagePreview" src="#" alt="Preview Foto" class="w-full h-full object-cover hidden">
                                <div id="imagePlaceholder" class="text-center text-blush-400 p-2">
                                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[9px] block">Belum ada foto</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="image" id="imageInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blush-100 file:text-blush-800 hover:file:bg-blush-200 cursor-pointer">
                            <p class="text-[11px] text-gray-400 mt-1">Pilih gambar baru untuk mengganti foto produk saat ini (Maks. 2 MB).</p>
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Pakaian / Busana <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kategori Busana <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kode SKU Unik <span class="text-rose-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800 font-mono">
                </div>

                <!-- Purchase Price (Modal) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga Beli / Modal (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="1000" min="0" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Selling Price -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga Jual Toko (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" step="1000" min="0" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Jumlah Stok Tersedia (pcs) <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Minimum Stock -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Batas Minimum Stok (Alert) <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi & Spesifikasi Produk</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-blush-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-blush-primary px-6 py-2.5 rounded-xl text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                const placeholder = document.getElementById('imagePlaceholder');
                preview.src = event.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
