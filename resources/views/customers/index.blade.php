@extends('layouts.app')

@section('title', 'Master Data Pelanggan')
@section('page_title', 'Master Data Pelanggan (Customers)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Pelanggan BlushBoutique</h3>
                <p class="text-xs text-gray-500">Kelola data pelanggan setia untuk kemudahan pencatatan transaksi penjualan kasir.</p>
            </div>
            <a href="{{ route('customers.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pelanggan Baru</span>
            </a>
        </div>

        <form method="GET" action="{{ route('customers.index') }}" class="pt-3 border-t border-blush-100 flex gap-3">
            <div class="relative flex-grow">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pelanggan, nomor telepon, email, atau alamat..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="py-2.5 px-4 rounded-xl bg-blush-500 hover:bg-blush-600 text-white text-xs font-semibold shadow-sm">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('customers.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 text-xs flex items-center justify-center" title="Reset Pencarian">
                    ↺
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">Nama Pelanggan</th>
                        <th class="py-3.5 px-4">No. Telepon / WA</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Alamat Domisili</th>
                        <th class="py-3.5 px-4 text-center">Total Transaksi</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blush-100 text-blush-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.show', $customer) }}" class="font-bold text-gray-900 hover:text-blush-600 transition-colors block">
                                            {{ $customer->name }}
                                        </a>
                                        <span class="text-[10px] text-gray-400">Terdaftar {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($customer->phone)
                                    <span class="font-mono font-medium text-blush-700">{{ $customer->phone }}</span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $customer->email ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 max-w-xs truncate" title="{{ $customer->address }}">
                                {{ $customer->address ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('customers.show', $customer) }}" class="inline-block px-2.5 py-1 rounded-full {{ $customer->sales_count > 0 ? 'bg-blush-100 text-blush-900 hover:bg-blush-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} font-semibold text-[10px] transition-colors" title="Lihat riwayat transaksi">
                                    {{ $customer->sales_count }} transaksi
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('customers.show', $customer) }}" class="inline-block p-1.5 rounded-lg bg-blush-50 hover:bg-blush-100 text-blush-700" title="Lihat Profil & Riwayat Belanja">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="inline-block p-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800" title="Edit Data Pelanggan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggan {{ $customer->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600" title="Hapus Pelanggan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                Belum ada data pelanggan yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection
