@extends('layouts.app')

@section('title', 'Katalog & Stok Produk')
@section('page_title', 'Master Produk & Stok Pakaian')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Busana & Manajemen Stok</h3>
                <p class="text-xs text-gray-500">Kelola informasi harga jual, harga modal, batas minimum stok, dan status ketersediaan.</p>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Produk Baru</span>
                </a>
            @endif
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-blush-100">
            <!-- Keyword Search -->
            <div class="sm:col-span-5 relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama busana atau SKU..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-3">
                <select name="category_id" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock Status Filter -->
            <div class="sm:col-span-2">
                <select name="stock_status" class="w-full px-3 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                    <option value="">Status Stok</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>⚠️ Stok Menipis</option>
                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Habis (0)</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-blush-500 hover:bg-blush-600 text-white text-xs font-semibold flex items-center justify-center space-x-1 shadow-sm">
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['q', 'category_id', 'stock_status']))
                    <a href="{{ route('products.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 flex items-center justify-center text-xs" title="Reset">
                        ↺
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">SKU</th>
                        <th class="py-3.5 px-4">Nama Pakaian</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        @if(auth()->user()->isAdmin())
                            <th class="py-3.5 px-4 text-right">Harga Modal</th>
                        @endif
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4 text-center">Min. Stok</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-blush-50/40 transition-colors {{ $product->isLowStock() ? 'bg-rose-50/30' : '' }}">
                            <td class="py-3.5 px-4 font-mono font-medium text-blush-800">
                                {{ $product->sku }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-blush-50 border border-blush-200 flex-shrink-0 overflow-hidden flex items-center justify-center shadow-sm">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs">👗</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block">{{ $product->name }}</span>
                                        <span class="text-[11px] text-gray-400 line-clamp-1">{{ $product->description }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full bg-blush-100 text-blush-800 text-[10px] font-semibold border border-blush-200">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="py-3.5 px-4 text-right text-gray-600 font-medium">
                                    {{ $product->formatted_purchase_price }}
                                </td>
                            @endif
                            <td class="py-3.5 px-4 text-right font-bold text-gray-900">
                                {{ $product->formatted_selling_price }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-bold text-sm {{ $product->stock <= 0 ? 'text-rose-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-gray-800') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-500">
                                {{ $product->minimum_stock }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($product->stock <= 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Habis
                                    </span>
                                @elseif($product->isLowStock())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                        Menipis
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('products.show', $product) }}" class="inline-block p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700" title="Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="inline-block p-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600" title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 9 : 8 }}" class="py-8 text-center text-gray-400">
                                Tidak ada produk busana yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
