@extends('layouts.app')

@section('title', 'Tambah Pelanggan Baru')
@section('page_title', 'Tambah Data Pelanggan Baru')

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

        <span class="text-xs text-gray-400">
            Formulir Pendaftaran Pelanggan Butik
        </span>
    </div>

    <!-- Kartu Informasi / Panduan Lembut (Soft Light Blue Pastel #EBF8FF) -->
    <div class="p-4 rounded-2xl bg-[#EBF8FF] border border-sky-200/80 text-sky-900 text-xs flex items-start space-x-3 shadow-xs">
        <div class="w-6 h-6 rounded-lg bg-sky-200/80 text-sky-700 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <span class="font-semibold block text-sky-950">Informasi Pendaftaran Pelanggan:</span>
            <p class="text-sky-800/90 mt-0.5">
                Pastikan memasukkan nama lengkap pelanggan dan nomor telepon aktif (WhatsApp) untuk memudahkan pengiriman bukti transaksi kasir atau penawaran busana eksklusif.
            </p>
        </div>
    </div>

    <!-- Form Tambah Pelanggan Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200/80 shadow-sm relative overflow-hidden">
        <!-- Header Formulir -->
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Formulir Tambah Pelanggan</h3>
                <p class="text-xs text-gray-500">Lengkapi kolom data pelanggan di bawah ini dengan valid.</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-[#FAF5F7] border border-blush-200 text-[#F27893] flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-5">
            @csrf

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
                           value="{{ old('name') }}" 
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
                           value="{{ old('phone') }}" 
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
                           value="{{ old('email') }}" 
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
                              class="w-full px-4 py-2.5 bg-[#FAF5F7] focus:bg-white rounded-xl border @error('address') border-rose-300 ring-1 ring-rose-200 @else border-blush-200 @enderror focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">{{ old('address') }}</textarea>
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

            <!-- Tombol Simpan & Batal/Kembali -->
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
                    <span>Simpan Pelanggan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
