@extends('layouts.app')

@section('title', 'Master Kategori')
@section('page_title', 'Master Kategori Busana (Admin)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Kategori Busana Pakaian</h3>
                <p class="text-xs text-gray-500">Kategori mempermudah pengelompokan dress, blouse, skirt, outerwear, dan hijab.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kategori Baru</span>
            </a>
        </div>

        <form method="GET" action="{{ route('categories.index') }}" class="pt-3 border-t border-blush-100 flex gap-3">
            <div class="relative flex-grow">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kategori atau deskripsi..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="py-2.5 px-4 rounded-xl bg-blush-500 hover:bg-blush-600 text-white text-xs font-semibold shadow-sm">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('categories.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 text-xs flex items-center justify-center">
                    ↺
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">Nama Kategori</th>
                        <th class="py-3.5 px-4">Slug URL</th>
                        <th class="py-3.5 px-4">Deskripsi</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Produk</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-gray-900">
                                {{ $cat->name }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-500">
                                {{ $cat->slug }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $cat->description ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-blush-100 text-blush-800 font-semibold text-[11px]">
                                    {{ $cat->products_count }} produk
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('categories.edit', $cat) }}" class="inline-block p-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                Belum ada kategori busana terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $categories->links() }}
        </div>
    </div>

</div>
@endsection
