<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - BlushBoutique Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush: {
                            50: '#fdf2f4',
                            100: '#fce7eb',
                            200: '#fbd0d9',
                            300: '#f7aab9',
                            400: '#f27893',
                            500: '#e54d71',
                            600: '#d03058',
                            700: '#b02044',
                            800: '#921d3b',
                            900: '#7a1d35',
                        },
                        pastel: {
                            cream: '#FFF9F5',
                            rose: '#FFF0F3',
                            peach: '#FFF5EB',
                            lavender: '#F8F5FF',
                            mint: '#F0FFF7',
                            slate: '#4A3B43',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FAF5F7;
            color: #372A31;
        }
        .blush-sidebar {
            background: linear-gradient(180deg, #FFFFFF 0%, #FFF5F7 100%);
            border-right: 1px solid #FCE7EB;
        }
        .active-nav {
            background: linear-gradient(135deg, #fce7eb 0%, #fbd0d9 100%);
            color: #921d3b !important;
            font-weight: 600;
            border-left: 4px solid #e54d71;
        }
        .btn-blush-primary {
            background: linear-gradient(135deg, #f27893 0%, #e54d71 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(229, 77, 113, 0.25);
            transition: all 0.2s ease;
        }
        .btn-blush-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(229, 77, 113, 0.35);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 blush-sidebar flex-shrink-0 flex flex-col justify-between p-5 min-h-screen">
        <div>
            <!-- Brand -->
            <div class="flex items-center space-x-3 px-2 py-3 mb-6 border-b border-blush-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blush-500 to-blush-300 flex items-center justify-center text-white shadow-md shadow-blush-300/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-blush-900 leading-tight">BlushBoutique</h1>
                    <span class="text-[11px] font-medium text-blush-500 tracking-wide uppercase">Inventory & Sales</span>
                </div>
            </div>

            <!-- User Info Badge -->
            <div class="px-3 py-3 mb-6 rounded-xl bg-white border border-blush-200 shadow-sm flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-blush-100 flex items-center justify-center text-blush-700 font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->isAdmin() ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-purple-100 text-purple-700 border border-purple-200' }}">
                        {{ auth()->user()->isAdmin() ? 'ADMIN / OWNER' : 'STAFF KASIR' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-x-0 space-y-1">
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-blush-400 mb-2">Utama</p>

                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-blush-400 mt-5 mb-2">Operasional & Transaksi</p>

                <!-- Sales -->
                <a href="{{ route('sales.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('sales.create') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Kasir / Penjualan Baru</span>
                </a>

                <a href="{{ route('sales.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('sales.index', 'sales.show') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Riwayat Penjualan</span>
                </a>

                <!-- Customers -->
                <a href="{{ route('customers.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('customers.*') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Pelanggan (Customers)</span>
                </a>

                <!-- Purchases -->
                <a href="{{ route('purchases.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('purchases.create') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Restock / Beli Stok</span>
                </a>

                <a href="{{ route('purchases.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('purchases.index', 'purchases.show') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Riwayat Pembelian</span>
                </a>

                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-blush-400 mt-5 mb-2">Inventory Pakaian</p>

                <!-- Products -->
                <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('products.*') ? 'active-nav' : '' }}">
                    <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Daftar Produk & Stok</span>
                </a>

                @if(auth()->user()->isAdmin())
                    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-rose-500 mt-5 mb-2">Master & Laporan (Admin)</p>

                    <!-- Categories -->
                    <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('categories.*') ? 'active-nav' : '' }}">
                        <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Kategori Busana</span>
                    </a>

                    <!-- Suppliers -->
                    <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('suppliers.*') ? 'active-nav' : '' }}">
                        <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Pemasok / Supplier</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('reports.sales') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('reports.sales') ? 'active-nav' : '' }}">
                        <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Laporan Laba & Penjualan</span>
                    </a>

                    <a href="{{ route('reports.inventory') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-blush-50 hover:text-blush-700 transition-colors {{ request()->routeIs('reports.inventory') ? 'active-nav' : '' }}">
                        <svg class="w-5 h-5 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Laporan Stok & Valuasi</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Bottom Links -->
        <div class="pt-6 border-t border-blush-100 space-y-2">
            <a href="{{ route('catalog.index') }}" target="_blank" class="flex items-center space-x-2 text-xs font-semibold px-3 py-2 rounded-lg text-blush-700 bg-white hover:bg-blush-100 transition-colors border border-blush-200">
                <svg class="w-4 h-4 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Web Pengunjung</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-2 text-xs font-semibold px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="bg-white/80 backdrop-blur-md border-b border-blush-100 sticky top-0 z-30 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-blush-900 leading-tight">@yield('page_title', 'Dashboard')</h2>
                <p class="text-xs text-gray-500">BlushBoutique Management Suite</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-xs text-gray-500 font-medium">
                    📅 {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-6 md:p-8 flex-grow">
            <!-- Flash Notification Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                    <div class="flex items-center space-x-2 font-semibold mb-2">
                        <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Terjadi kesalahan input pada formulir:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
