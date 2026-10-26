@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')
@section('page_title', 'Tambah Kategori Busana')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Form Kategori Baru</h3>
                <p class="text-xs text-gray-500">Slug URL akan dibuat secara otomatis dari nama kategori.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="contoh: Outerwear & Blazers" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Penjelasan jenis pakaian pada kategori ini..." class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 border-t border-blush-100 flex items-center justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-blush-primary px-6 py-2.5 rounded-xl text-xs font-semibold">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
