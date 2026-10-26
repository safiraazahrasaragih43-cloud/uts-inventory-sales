@extends('layouts.app')

@section('title', 'Ubah Data Pelanggan - ' . $customer->name)
@section('page_title', 'Ubah Data Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Navigasi Breadcrumb / Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('customers.index') }}" 
           class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-500 hover:text-[#F27893] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Pelanggan</span>
        </a>

        <a href="{{ route('customers.show', $customer) }}" 
           class="inline-flex items-center space-x-1.5 text-xs font-semibold text-sky-700 hover:text-sky-800">
            <span>Lihat Profil Pelanggan</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>

    <!-- Ringkasan Info Pelanggan Singkat (Soft Green Pastel #F0FFF4) -->
    <div class="p-4 rounded-2xl bg-[#F0FFF4] border border-emerald-200/80 text-emerald-900 text-xs flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-200/70 text-emerald-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <span class="font-bold text-gray-900 block text-xs">Mengedit Data: {{ $customer->name }}</span>
                <span class="text-[11px] text-emerald-800">
                    ID Pelanggan: #{{ $customer->id }} &bull; Terdaftar sejak {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                </span>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full bg-white text-emerald-700 text-[11px] font-semibold border border-emerald-200">
            🌸 Member Blush
        </span>
    </div>

    <!-- Form Ubah Pelanggan Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200/80 shadow-sm relative overflow-hidden">
        <!-- Header Formulir -->
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Perbarui Informasi Pelanggan</h3>
                <p class="text-xs text-gray-500">Perbarui kontak dan alamat untuk pembaruan profil pembeli butik.</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-[#FAF5F7] border border-blush-200 text-[#F27893] flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
        </div>

        <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- 1. Input: Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-rose-500 font-bold">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="name"
                           name="name" 
                           value="{{ old('name', $customer->name) }}" 
                           required 
                           placeholder="Contoh: Safira Aurelia" 
                           class="w-full pl-10 pr-4 py-2.5 bg-[#FAF5F7] focus:bg-white rounded-xl border @error('name') border-rose-300 ring-1 ring-rose-200 @else border-blush-200 @enderror focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">
                </div>
                @error('name')
                    <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- 2. Input: Nomor Telepon -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Nomor Telepon / WhatsApp
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="phone"
                           name="phone" 
                           value="{{ old('phone', $customer->phone) }}" 
                           placeholder="Contoh: 081234567890" 
                           class="w-full pl-10 pr-4 py-2.5 bg-[#FAF5F7] focus:bg-white rounded-xl border @error('phone') border-rose-300 ring-1 ring-rose-200 @else border-blush-200 @enderror focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Gunakan nomor aktif untuk kemudahan kirim struk belanja via WhatsApp.</p>
                @error('phone')
                    <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- 3. Input: Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email', $customer->email) }}" 
                           placeholder="Contoh: safira@email.com" 
                           class="w-full pl-10 pr-4 py-2.5 bg-[#FAF5F7] focus:bg-white rounded-xl border @error('email') border-rose-300 ring-1 ring-rose-200 @else border-blush-200 @enderror focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- 4. Input: Alamat (Textarea) -->
            <div>
                <label for="address" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Alamat Lengkap (Domisili)
                </label>
                <div class="relative">
                    <textarea id="address"
                              name="address" 
                              rows="3" 
                              placeholder="Contoh: Jl. Diponegoro No. 45, Bandung, Jawa Barat" 
                              class="w-full px-4 py-2.5 bg-[#FAF5F7] focus:bg-white rounded-xl border @error('address') border-rose-300 ring-1 ring-rose-200 @else border-blush-200 @enderror focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">{{ old('address', $customer->address) }}</textarea>
                </div>
                @error('address')
                    <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Tombol Simpan Perubahan & Batal/Kembali -->
            <div class="pt-5 border-t border-blush-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('customers.index') }}" 
                   class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold transition-colors text-center">
                    Batal / Kembali
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#F27893] hover:bg-[#e54d71] text-white text-xs font-semibold shadow-md shadow-blush-300/40 transition-all flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
