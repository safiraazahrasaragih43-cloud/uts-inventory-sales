<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - BlushBoutique Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
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
</head>
<body class="min-h-screen bg-gradient-to-br from-blush-100 via-rose-50 to-blush-200 flex items-center justify-center p-4 antialiased">

    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blush-500 to-blush-400 flex items-center justify-center text-white shadow-lg shadow-blush-300 mx-auto mb-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h1 class="font-serif text-3xl font-bold text-blush-900">BlushBoutique</h1>
            <p class="text-xs font-medium text-blush-600 uppercase tracking-widest mt-1">Sistem Inventory & Kasir Penjualan</p>
        </div>

        <!-- Card Form -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-xl shadow-blush-500/10 border border-blush-200">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Masuk ke Sistem</h2>
            <p class="text-xs text-gray-500 mb-6">Silakan masukkan email dan kata sandi akun Anda.</p>

            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center space-x-2">
                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh: admin@blushboutique.com" class="w-full px-4 py-3 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-sm text-gray-800 transition-all @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-blush-50/40 rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-sm text-gray-800 transition-all">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-blush-600 focus:ring-blush-400">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blush-500 to-blush-600 hover:from-blush-600 hover:to-blush-700 text-white font-semibold text-sm shadow-md shadow-blush-300 transition-all">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Demo 1-Click Credentials Helper -->
            <div class="mt-6 pt-6 border-t border-blush-100">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2.5 text-center">Akun Demo (Klik untuk Isi Otomatis):</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <button type="button" onclick="fillCreds('admin@blushboutique.com', 'password123')" class="p-2.5 rounded-xl bg-blush-50 hover:bg-blush-100 text-blush-800 border border-blush-200 text-xs text-left transition-colors">
                        <div class="font-bold flex items-center space-x-1"><span>👑 Admin</span></div>
                        <div class="text-[10px] text-gray-500">Master & Laba</div>
                    </button>
                    <button type="button" onclick="fillCreds('staff@blushboutique.com', 'password123')" class="p-2.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 text-xs text-left transition-colors">
                        <div class="font-bold flex items-center space-x-1"><span>🛍️ Staff</span></div>
                        <div class="text-[10px] text-gray-500">Kasir & Stok</div>
                    </button>
                    <button type="button" onclick="fillCreds('customer@blushboutique.com', 'password123')" class="p-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 text-xs text-left transition-colors">
                        <div class="font-bold flex items-center space-x-1"><span>🌸 Customer</span></div>
                        <div class="text-[10px] text-gray-500">Member Butik</div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Back to Public Site -->
        <div class="text-center mt-6">
            <a href="{{ route('catalog.index') }}" class="text-xs font-medium text-blush-800 hover:text-blush-900 transition-colors inline-flex items-center space-x-1">
                <span>← Kembali ke Katalog Web BlushBoutique</span>
            </a>
        </div>
    </div>

    <script>
        function fillCreds(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
