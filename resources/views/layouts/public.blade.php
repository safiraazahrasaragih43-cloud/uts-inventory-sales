<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Katalog Koleksi Pakaian' }} - BlushBoutique</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FDF7F8;
            color: #372A31;
        }
        .font-serif-luxury {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .blush-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(251, 208, 217, 0.5);
        }
        .blush-gradient {
            background: linear-gradient(135deg, #FDE8ED 0%, #FFF5F7 50%, #FEEAF0 100%);
        }
        .blush-btn {
            background: linear-gradient(135deg, #f27893 0%, #e54d71 100%);
            box-shadow: 0 4px 15px rgba(229, 77, 113, 0.25);
            transition: all 0.3s ease;
        }
        .blush-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 77, 113, 0.35);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-blush-200 via-blush-100 to-blush-200 py-2 px-4 text-center text-xs sm:text-sm font-medium text-blush-900 border-b border-blush-200">
        🌸 Selamat datang di <span class="font-semibold">BlushBoutique</span> — Koleksi Fashion Elegan Pastel & Feminin. Pemesanan Langsung via WhatsApp.
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 blush-glass shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blush-500 to-blush-300 flex items-center justify-center text-white shadow-md shadow-blush-300/50 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-serif-luxury text-2xl font-bold tracking-tight text-blush-900 block leading-tight">BlushBoutique</span>
                        <span class="text-[10px] tracking-widest uppercase font-medium text-blush-400">Atelier & Apparel</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-blush-800 hover:text-blush-600 transition-colors">Katalog Koleksi</a>
                    <a href="#kategori" class="text-sm font-medium text-gray-600 hover:text-blush-600 transition-colors">Kategori</a>
                    <a href="#tentang" class="text-sm font-medium text-gray-600 hover:text-blush-600 transition-colors">Tentang Kami</a>
                    <a href="#kontak" class="text-sm font-medium text-gray-600 hover:text-blush-600 transition-colors">Kontak & Lokasi</a>
                </nav>

                <!-- Actions / Auth Link -->
                <div class="flex items-center space-x-3">
                    <!-- Shopping Cart Trigger Button -->
                    <button type="button" onclick="openCartDrawer()" class="relative p-2.5 rounded-xl bg-white hover:bg-blush-50 text-blush-800 border border-blush-200 shadow-sm transition-all flex items-center space-x-2 group" title="Buka Keranjang Pesanan">
                        <svg class="w-5 h-5 text-blush-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 hidden sm:inline">Keranjang</span>
                        <span id="headerCartCount" class="w-5 h-5 rounded-full bg-blush-600 text-white text-[10px] font-bold flex items-center justify-center shadow-sm">0</span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 text-xs font-semibold px-4 py-2.5 rounded-xl bg-blush-100 text-blush-800 hover:bg-blush-200 transition-colors border border-blush-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Dashboard ({{ ucfirst(auth()->user()->role) }})</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 text-xs font-semibold px-4 py-2.5 rounded-xl bg-white text-blush-700 hover:bg-blush-50 transition-colors border border-blush-200 shadow-sm">
                            <svg class="w-4 h-4 text-blush-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Staff / Admin</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer id="kontak" class="bg-white border-t border-blush-100 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blush-500 flex items-center justify-center text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <span class="font-serif-luxury text-xl font-bold text-blush-900">BlushBoutique</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-sm mb-6">
                        Menghadirkan pesona busana anggun bernuansa pastel soft, bahan premium berkualitas tinggi, dan siluet feminin untuk setiap momen istimewa Anda.
                    </p>
                    <div class="flex space-x-3 text-xs text-blush-800">
                        <span class="px-3 py-1 bg-blush-50 rounded-full border border-blush-200">✨ 100% Produk Original</span>
                        <span class="px-3 py-1 bg-blush-50 rounded-full border border-blush-200">🎀 Fast Response WA</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-sm text-blush-900 tracking-wider uppercase mb-4">Jam Operasional</h4>
                    <p class="text-sm text-gray-600 mb-2 font-medium">Boutique & Layanan CS:</p>
                    <p class="text-sm text-gray-500">Senin - Sabtu: 09:00 - 20:00 WIB</p>
                    <p class="text-sm text-gray-500">Minggu: 10:00 - 18:00 WIB</p>
                    <div class="mt-4 pt-4 border-t border-blush-100">
                        <span class="text-xs text-gray-500">Lokasi: Jl. Kenanga Indah No. 18, Bandung</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-sm text-blush-900 tracking-wider uppercase mb-4">Pusat Bantuan & Order</h4>
                    <p class="text-xs text-gray-500 mb-4">Untuk pemesanan langsung, konsultasi ukuran pakaian, atau cek ketersediaan stok produk:</p>
                    <a href="https://wa.me/6281234567890?text=Halo%20BlushBoutique,%20saya%20ingin%20bertanya%20mengenai%20koleksi%20pakaian" target="_blank" class="inline-flex items-center space-x-2 text-xs font-semibold px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.302c-.087.086-.179.18-.077.355.101.174.449.741.964 1.2.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.505.549-.679.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                        <span>Chat WhatsApp Kami</span>
                    </a>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-blush-100 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} BlushBoutique. All rights reserved. Elegance in Every Thread.</p>
                <p class="mt-2 sm:mt-0">Inventory & Sales Management System</p>
            </div>
        </div>
    </footer>

</body>
</html>
