@extends('layouts.app')

@section('title', 'Daftar Pelanggan')
@section('page_title', 'Kelola Data Pelanggan')

@section('content')
<div class="space-y-6">

    <!-- Header & Ringkasan Kartu Pastel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Kartu Total Pelanggan (Light Blue Pastel) -->
        <div class="bg-white rounded-2xl p-5 border border-sky-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="space-y-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-sky-600">Total Pelanggan Terdaftar</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $customers->total() }}</h3>
                <p class="text-[11px] text-gray-400">Database pelanggan butik</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EBF8FF] text-sky-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-sky-200/60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

        <!-- Kartu Pelanggan Aktif (Soft Green Pastel) -->
        <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="space-y-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Pelanggan Aktif Bertransaksi</span>
                <h3 class="text-2xl font-bold text-gray-800">
                    {{ $customers->where('sales_count', '>', 0)->count() }}
                </h3>
                <p class="text-[11px] text-gray-400">Pernah belanja di kasir</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F0FFF4] text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-emerald-200/60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
        </div>

        <!-- Kartu Aksen Blush Pink (#F27893) -->
        <div class="bg-gradient-to-r from-white via-white to-blush-50/50 rounded-2xl p-5 border border-blush-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#F27893]">Loyalti Blush Boutique</span>
                <h3 class="text-lg font-bold text-blush-900">Member Butik</h3>
                <p class="text-[11px] text-gray-500">Pencatatan riwayat otomatis</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blush-100 text-[#F27893] flex items-center justify-center flex-shrink-0 shadow-sm border border-blush-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Kotak Aksi & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center space-x-2">
                    <span>Daftar Pelanggan BlushBoutique</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#FAF5F7] text-[#F27893] border border-blush-200 font-semibold">
                        {{ $customers->total() }} Pelanggan
                    </span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Kelola data pelanggan untuk kemudahan transaksi kasir dan riwayat pembelian busana.</p>
            </div>

            <!-- Tombol Tambah Pelanggan Baru (Blush Pink #F27893) -->
            <a href="{{ route('customers.create') }}" 
               class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#F27893] hover:bg-[#e54d71] shadow-md shadow-blush-300/40 transition-all transform hover:-translate-y-0.5 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Pelanggan Baru</span>
            </a>
        </div>

        <!-- Form Fitur Pencarian (Search Bar berdasarkan Nama atau Nomor Telepon) -->
        <form method="GET" action="{{ route('customers.index') }}" class="pt-4 border-t border-blush-100 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#F27893]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari berdasarkan nama atau nomor telepon..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-[#FAF5F7] hover:bg-white focus:bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-[#F27893] focus:border-transparent text-xs text-gray-800 transition-colors">
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#F27893] hover:bg-[#e54d71] text-white text-xs font-semibold shadow-sm transition-all flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari</span>
                </button>

                @if(request('q'))
                    <a href="{{ route('customers.index') }}" 
                       class="px-3.5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-colors flex items-center space-x-1" 
                       title="Reset Hasil Pencarian">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="text-xs text-gray-500 pt-1 flex items-center space-x-1.5">
                <span>Hasil pencarian untuk kata kunci:</span>
                <span class="font-semibold text-blush-700 bg-blush-100 px-2 py-0.5 rounded-md">"{{ request('q') }}"</span>
            </div>
        @endif
    </div>

    <!-- Tabel Daftar Pelanggan -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-[#FAF5F7] text-blush-900 border-b border-blush-200 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="py-4 px-4 text-center w-12">No</th>
                        <th class="py-4 px-4">Nama Pelanggan</th>
                        <th class="py-4 px-4">No. Telepon</th>
                        <th class="py-4 px-4">Email</th>
                        <th class="py-4 px-4">Alamat</th>
                        <th class="py-4 px-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100 text-gray-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-[#FAF5F7]/70 transition-colors">
                            <!-- Kolom 1: No -->
                            <td class="py-4 px-4 text-center font-medium text-gray-500">
                                {{ ($customers->firstItem() ?? 1) + $loop->index }}
                            </td>

                            <!-- Kolom 2: Nama Pelanggan -->
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blush-200 to-blush-100 text-[#F27893] border border-blush-200 font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}" class="font-semibold text-gray-900 hover:text-[#F27893] transition-colors block">
                                            {{ $customer->name }}
                                        </a>
                                        <span class="text-[10px] text-gray-400">
                                            Terdaftar: {{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 3: No. Telepon -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($customer->phone)
                                    <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-[#EBF8FF] text-sky-800 border border-sky-200/70 font-mono text-xs">
                                        <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>{{ $customer->phone }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Kolom 4: Email -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($customer->email)
                                    <div class="flex items-center space-x-1.5 text-gray-600">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Kolom 5: Alamat -->
                            <td class="py-4 px-4 max-w-xs">
                                @if($customer->address)
                                    <p class="truncate text-gray-600" title="{{ $customer->address }}">
                                        {{ $customer->address }}
                                    </p>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Kolom 6: Aksi (Detail, Edit, Hapus) -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center space-x-1.5">
                                    <!-- Aksi Detail -->
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-lg bg-[#EBF8FF] hover:bg-sky-100 text-sky-700 border border-sky-200 text-xs font-medium transition-colors" 
                                       title="Lihat Detail Profil & Riwayat">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Aksi Edit -->
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-lg bg-[#FAF5F7] hover:bg-blush-100 text-[#F27893] border border-blush-200 text-xs font-medium transition-colors" 
                                       title="Edit Data Pelanggan">
                                        <svg class="w-3.5 h-3.5 text-[#F27893]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Aksi Hapus -->
                                    <form action="{{ route('customers.destroy', $customer) }}" 
                                          method="POST" 
                                          class="inline-block" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan {{ addslashes($customer->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-medium transition-colors" 
                                                title="Hapus Pelanggan">
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-14 h-14 rounded-full bg-[#FAF5F7] border border-blush-200 text-[#F27893] mx-auto flex items-center justify-center">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Data Pelanggan Tidak Ditemukan</p>
                                    <p class="text-xs text-gray-400">
                                        @if(request('q'))
                                            Tidak ada pelanggan yang cocok dengan kata kunci "{{ request('q') }}". Coba cari dengan kata kunci lain.
                                        @else
                                            Belum ada data pelanggan yang terdaftar dalam sistem butik ini.
                                        @endif
                                    </p>
                                    <div class="pt-2">
                                        @if(request('q'))
                                            <a href="{{ route('customers.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700 transition-colors">
                                                <span>Bersihkan Filter Pencarian</span>
                                            </a>
                                        @else
                                            <a href="{{ route('customers.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#F27893] hover:bg-[#e54d71] text-xs font-semibold text-white shadow-sm transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                <span>Tambah Pelanggan Baru</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Komponen Pagination di Bagian Bawah Tabel -->
        @if($customers->hasPages())
            <div class="p-4 border-t border-blush-100 bg-[#FAF5F7]/40 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="text-gray-500">
                    Menampilkan data <span class="font-semibold text-gray-800">{{ $customers->firstItem() }}</span> sampai <span class="font-semibold text-gray-800">{{ $customers->lastItem() }}</span> dari total <span class="font-semibold text-gray-800">{{ $customers->total() }}</span> pelanggan
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
