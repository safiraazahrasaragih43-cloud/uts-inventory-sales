@extends('layouts.app')

@section('title', 'Ubah Supplier: ' . $supplier->name)
@section('page_title', 'Perbarui Data Supplier')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Ubah: {{ $supplier->name }}</h3>
                <p class="text-xs text-gray-500">Perbarui informasi kontak dan alamat supplier.</p>
            </div>
            <a href="{{ route('suppliers.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Perusahaan / Supplier <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kontak Person (PIC)</label>
                <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Gudang / Kantor</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div class="pt-4 border-t border-blush-100 flex items-center justify-end space-x-3">
                <a href="{{ route('suppliers.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-blush-primary px-6 py-2.5 rounded-xl text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
