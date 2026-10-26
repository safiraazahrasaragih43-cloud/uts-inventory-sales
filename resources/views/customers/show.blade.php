@extends('layouts.app')

@section('title', 'Profil Pelanggan: ' . $customer->name)
@section('page_title', 'Detail & Riwayat Belanja Pelanggan')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Action Buttons -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-blush-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Pelanggan</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <!-- New Sale for this Customer -->
            <a href="{{ route('sales.create', ['customer_id' => $customer->id]) }}" class="btn-blush-primary px-4 py-2 rounded-xl text-xs font-semibold flex items-center space-x-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Transaksi Kasir Baru</span>
            </a>

            <!-- Edit Customer -->
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 rounded-xl bg-white text-blush-800 hover:bg-blush-50 border border-blush-300 text-xs font-semibold flex items-center space-x-1.5 shadow-sm transition-colors">
                <svg class="w-3.5 h-3.5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Profil</span>
            </a>

            <!-- Delete Customer -->
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggan {{ $customer->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-semibold flex items-center space-x-1.5 transition-colors" title="Hapus Pelanggan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Customer Profile Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200/80 shadow-sm relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-gradient-to-br from-blush-100/50 to-blush-200/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center space-x-5">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blush-400 to-blush-200 text-white font-bold text-3xl flex items-center justify-center shadow-lg shadow-blush-200/50 flex-shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-xl sm:text-2xl font-bold text-blush-900 font-serif">{{ $customer->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blush-100 text-blush-700 border border-blush-200">
                            🌸 Member Aktif
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Terdaftar sejak: <span class="font-semibold text-gray-700">{{ $customer->created_at ? $customer->created_at->translatedFormat('d F Y') : '-' }}</span>
                    </p>
                </div>
            </div>

            <!-- Contact Actions -->
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                @if($customer->phone)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $customer->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold flex items-center space-x-2 shadow-sm transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Hubungi via WA</span>
                    </a>
                @endif
                @if($customer->email)
                    <a href="mailto:{{ $customer->email }}" class="px-3.5 py-2 rounded-xl bg-blush-50 text-blush-800 hover:bg-blush-100 border border-blush-200 text-xs font-semibold flex items-center space-x-1.5 transition-colors">
                        <svg class="w-4 h-4 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Kirim Email</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Contact Detail Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 mt-6 border-t border-blush-100 text-xs">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blush-50 flex items-center justify-center text-blush-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">No. Telepon / WhatsApp:</span>
                    <span class="font-semibold text-gray-800 font-mono">{{ $customer->phone ?: 'Belum diisi' }}</span>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blush-50 flex items-center justify-center text-blush-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Alamat Email:</span>
                    <span class="font-semibold text-gray-800">{{ $customer->email ?: 'Belum diisi' }}</span>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blush-50 flex items-center justify-center text-blush-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Alamat Domisili:</span>
                    <span class="font-semibold text-gray-800">{{ $customer->address ?: 'Belum diisi' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Spending & Order Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Spent -->
        <div class="bg-white rounded-3xl p-5 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Total Akumulasi Belanja</p>
                <h3 class="text-xl font-bold text-blush-900 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">Sepanjang waktu</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blush-100 text-blush-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-3xl p-5 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Total Transaksi</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $customer->sales_count }} <span class="text-xs font-normal text-gray-500">faktur</span></h3>
                <p class="text-[10px] text-gray-400 mt-0.5">Kunjungan kasir selesai</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- Total Clothing Units -->
        <div class="bg-white rounded-3xl p-5 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Pcs Busana Dibeli</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ number_format($totalItemsCount, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">pcs</span></h3>
                <p class="text-[10px] text-gray-400 mt-0.5">Total helai pakaian</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>

        <!-- Average Basket Value -->
        <div class="bg-white rounded-3xl p-5 border border-blush-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Rata-rata Transaksi</p>
                <h3 class="text-xl font-bold text-emerald-700 mt-1">Rp {{ number_format($averageSpent, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">Per kali berbelanja</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    <!-- Purchase History Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Riwayat Transaksi Belanja</h3>
                <p class="text-xs text-gray-500">Daftar semua faktur dan busana yang pernah dibeli oleh {{ $customer->name }}.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-blush-50 text-blush-800 font-semibold text-xs border border-blush-200">
                {{ $customer->sales->count() }} Faktur Ditemukan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">No. Faktur</th>
                        <th class="py-3.5 px-4">Tanggal Transaksi</th>
                        <th class="py-3.5 px-4">Kasir</th>
                        <th class="py-3.5 px-4">Item Busana yang Dibeli</th>
                        <th class="py-3.5 px-4">Metode Bayar</th>
                        <th class="py-3.5 px-4 text-right">Total Belanja</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($customer->sales as $sale)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-blush-800 whitespace-nowrap">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 whitespace-nowrap">
                                {{ $sale->sale_date->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-semibold text-gray-700">
                                    {{ $sale->user->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    @foreach($sale->items as $item)
                                        <div class="flex items-center space-x-2 text-[11px]">
                                            <span class="font-medium text-gray-800">{{ $item->product->name }}</span>
                                            <span class="text-gray-400">×{{ $item->quantity }}</span>
                                            <span class="text-[10px] text-gray-400">({{ $item->formatted_unit_price }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-gray-900 text-sm whitespace-nowrap">
                                {{ $sale->formatted_total_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800 font-semibold text-[11px] transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Struk</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-blush-50 text-blush-400 mx-auto flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium">Pelanggan ini belum memiliki riwayat transaksi pembelian.</p>
                                    <a href="{{ route('sales.create', ['customer_id' => $customer->id]) }}" class="inline-block btn-blush-primary px-4 py-2 rounded-xl text-xs font-semibold">
                                        Mulai Transaksi Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
