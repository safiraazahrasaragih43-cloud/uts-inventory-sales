@extends('layouts.app')

@section('title', 'Ubah Data Pelanggan')
@section('page_title', 'Ubah Data Pelanggan')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Edit Data Pelanggan</h3>
                <p class="text-xs text-gray-500">Perbarui kontak dan detail informasi pelanggan {{ $customer->name }}.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Kembali
            </a>
        </div>

        <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap Pelanggan <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required placeholder="contoh: Clarissa Stephanie" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="contoh: 081234567890" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <p class="text-[11px] text-gray-400 mt-1">Opsional, berguna untuk konfirmasi pesanan atau struk digital.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="contoh: clarissa@gmail.com" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Domisili</label>
                <textarea name="address" rows="3" placeholder="contoh: Jl. Dago No. 12, Bandung" class="w-full px-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="pt-4 border-t border-blush-100 flex items-center justify-end space-x-3">
                <a href="{{ route('customers.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
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
