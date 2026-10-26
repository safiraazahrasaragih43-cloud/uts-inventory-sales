@extends('layouts.app')

@section('title', 'Master Supplier')
@section('page_title', 'Master Supplier & Pemasok Pakaian (Admin)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white rounded-3xl p-6 border border-blush-200/80 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">Daftar Supplier Mitra Busana</h3>
                <p class="text-xs text-gray-500">Kelola informasi kontak vendor, konveksi, dan distributor pakaian.</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn-blush-primary px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Supplier Baru</span>
            </a>
        </div>

        <form method="GET" action="{{ route('suppliers.index') }}" class="pt-3 border-t border-blush-100 flex gap-3">
            <div class="relative flex-grow">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama supplier, kontak person, nomor telepon..." class="w-full pl-10 pr-4 py-2.5 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                <svg class="w-4 h-4 text-blush-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="py-2.5 px-4 rounded-xl bg-blush-500 hover:bg-blush-600 text-white text-xs font-semibold shadow-sm">
                Cari
            </button>
            @if(request('q'))
                <a href="{{ route('suppliers.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 text-xs flex items-center justify-center">
                    ↺
                </a>
            @endif
        </form>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-3xl border border-blush-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-blush-50/70 text-blush-900 border-b border-blush-200 uppercase font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">Nama Supplier</th>
                        <th class="py-3.5 px-4">Kontak Person</th>
                        <th class="py-3.5 px-4">Telepon / WA</th>
                        <th class="py-3.5 px-4">Alamat</th>
                        <th class="py-3.5 px-4 text-center">Riwayat Restock</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blush-100">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-blush-50/40 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-900 block">{{ $supplier->name }}</span>
                                <span class="text-[10px] text-gray-400">{{ $supplier->email ?: 'Tidak ada email' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-gray-700">
                                {{ $supplier->contact_person ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-medium text-blush-700">
                                {{ $supplier->phone }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $supplier->address ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 font-semibold text-[10px]">
                                    {{ $supplier->purchases_count }} transaksi
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-block p-1.5 rounded-lg bg-blush-100 hover:bg-blush-200 text-blush-800" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier {{ $supplier->name }}?');">
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
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                Belum ada supplier atau vendor terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-blush-100 bg-white">
            {{ $suppliers->links() }}
        </div>
    </div>

</div>
@endsection
