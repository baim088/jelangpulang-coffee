<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $title ?? 'JelangPulang — Satu gelas sebelum arah pulang' }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;1,700&family=Fira+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    
    <!-- Alpine.js & Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#612b0f",
                        "primary-container": "#7e4124",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#ffb393",
                        secondary: "#89511c",
                        "secondary-container": "#ffb376",
                        "on-secondary-container": "#79430e",
                        "secondary-fixed": "#ffdcc3",
                        surface: "#fdf9f4",
                        "surface-bright": "#fdf9f4",
                        "surface-container": "#f1ede8",
                        "surface-container-low": "#f7f3ee",
                        "surface-container-high": "#ebe8e3",
                        "surface-container-highest": "#e6e2dd",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#1c1c19",
                        "on-surface-variant": "#53433d",
                        outline: "#86736c",
                        "outline-variant": "#d8c2b9",
                        error: "#ba1a1a",
                        "error-container": "#ffdad6"
                    },
                    fontFamily: {
                        display: ["'Barlow Condensed'", "sans-serif"],
                        headline: ["'Barlow Condensed'", "sans-serif"],
                        body: ["'Fira Sans'", "sans-serif"],
                        price: ["'Barlow Condensed'", "sans-serif"]
                    }
                }
            }
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .font-price { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; }
        .font-headline { font-family: 'Barlow Condensed', sans-serif; }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md shadow-[0_1px_8px_rgba(92,35,8,0.06)]">
        <div class="h-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="JELANGPULANG" class="h-11 w-auto object-contain">
                <span class="sr-only">JELANGPULANG</span>
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold {{ request()->routeIs('catalog.*') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Katalog Menu
                </a>
                @auth
                    <a href="{{ route('order.history') }}" class="text-sm font-semibold {{ request()->routeIs('order.history') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                        Riwayat Pesanan
                    </a>
                    @if(auth()->user()->isKasir())
                        <a href="/kasir/antrean" class="text-xs font-bold px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-container">
                            Layar Kasir
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="/admin/dashboard" class="text-xs font-bold px-3 py-1 rounded-full bg-primary-container text-on-primary">
                            Layar Admin
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- Actions (Cart & Auth) -->
            <div class="flex items-center gap-3">
                <!-- Cart Icon Button -->
                @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-full bg-surface-container hover:bg-surface-container-high transition-colors flex items-center justify-center text-primary" title="Lihat Keranjang">
                    <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 px-2 py-0.5 rounded-full bg-secondary text-white text-[11px] font-bold font-headline shadow-sm animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Auth Info or Login -->
                @auth
                    <div class="flex items-center gap-2 pl-2 border-l border-outline-variant/40">
                        <span class="hidden sm:inline text-xs font-medium text-on-surface-variant">{{ auth()->user()->name }}</span>
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-on-surface-variant hover:text-error transition-colors" title="Keluar">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="hidden sm:inline-flex items-center justify-center font-semibold text-xs px-4 py-2 rounded-full border border-primary/20 text-primary hover:bg-surface-container transition-all">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-none">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-primary text-on-primary shadow-xl flex items-center gap-3 pointer-events-auto border border-primary-container animate-fade-in">
                <span class="material-symbols-outlined text-secondary-fixed">check_circle</span>
                <span class="text-sm font-medium flex-1">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-error text-white shadow-xl flex items-center gap-3 pointer-events-auto animate-fade-in">
                <span class="material-symbols-outlined">error</span>
                <span class="text-sm font-medium flex-1">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 w-full pt-20">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation for Mobile -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-lg border-t border-outline-variant/30 px-6 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[22px]">home</span>
            <span>Beranda</span>
        </a>
        <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('catalog.*') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[22px]">restaurant_menu</span>
            <span>Katalog</span>
        </a>
        <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('cart.*') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
            <span>Pesanan</span>
            @if($cartCount > 0)
                <span class="absolute -top-1 right-2 w-4 h-4 rounded-full bg-secondary text-white text-[10px] flex items-center justify-center font-bold">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
        @auth
            <a href="{{ route('order.history') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('order.history') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[22px]">history</span>
                <span>Riwayat</span>
            </a>
        @else
            <a href="/login" class="flex flex-col items-center gap-0.5 text-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-[22px]">person</span>
                <span>Masuk</span>
            </a>
        @endauth
    </div>

    <!-- Footer -->
    <footer class="bg-surface-container-highest/60 border-t border-outline-variant/30 pt-12 pb-24 md:pb-12 mt-16 text-on-surface-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2 space-y-3">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="JelangPulang Coffee" class="h-9 w-auto object-contain">
                </div>
                <p class="text-sm leading-relaxed max-w-sm">
                    Kedai kopi sore hari. Jeda sejenak untuk melaraskan rasa dan menghela napas sebelum perjalanan kembali pulang ke rumah.
                </p>
                <div class="flex items-center gap-2 text-xs text-secondary font-semibold pt-1">
                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                    <span>Buka Setiap Hari: 15:00 - 22:00 WIB</span>
                </div>
            </div>

            <div>
                <h4 class="font-headline text-lg font-bold text-primary mb-3 uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-primary transition-colors">Semua Menu</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-primary transition-colors">Keranjang Belanja</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline text-lg font-bold text-primary mb-3 uppercase tracking-wider">Kontak & Lokasi</h4>
                <p class="text-sm leading-relaxed mb-2">Jl. Sore Hari No. 12, Senja Raya</p>
                <p class="text-sm font-semibold text-primary">WhatsApp: 0812-3456-7890</p>
                <p class="text-xs text-on-surface-variant/70 mt-4">
                    &copy; {{ date('Y') }} JelangPulang Coffee. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
