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
                <a href="{{ route('about') }}" class="text-sm font-semibold {{ request()->routeIs('about') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('contact') }}" class="text-sm font-semibold {{ request()->routeIs('contact') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                    Kontak
                </a>
                @if(auth()->check() && auth()->user()->role === 'user')
                    <a href="{{ route('order.history') }}" class="text-sm font-semibold {{ request()->routeIs('order.history') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
                        Riwayat Pesanan
                    </a>
                @endif
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

                <!-- Auth Info with User Dropdown -->
                @auth
                    <div x-data="{ openDropdown: false }" class="relative pl-2 border-l border-outline-variant/40">
                        <!-- Dropdown Trigger Button -->
                        <button type="button" @click="openDropdown = !openDropdown" 
                                class="flex items-center gap-2 p-1.5 rounded-full hover:bg-surface-container transition-all focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-headline font-bold text-sm shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-semibold text-primary truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-on-surface-variant capitalize leading-none">{{ auth()->user()->role }}</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform" :class="openDropdown ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <!-- Dropdown Menu Box -->
                        <div x-cloak x-show="openDropdown" @click.away="openDropdown = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 top-full mt-2 w-64 rounded-2xl bg-surface-container-lowest shadow-2xl border border-outline-variant/40 py-2 z-50">
                            
                            <!-- User Mini Card -->
                            <div class="px-4 py-3 border-b border-outline-variant/20 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-headline font-bold text-base shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-0.5 px-2 py-0.2 rounded-full text-[9px] font-bold uppercase tracking-wider bg-secondary-fixed text-on-secondary-container">
                                        {{ auth()->user()->role }}
                                    </span>
                                </div>
                            </div>

                            <!-- Menu Links -->
                            <div class="py-1">
                                @if(auth()->user()->role === 'user')
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-on-surface hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-primary">dashboard</span>
                                        <span>Dashboard Akun</span>
                                    </a>

                                    <a href="{{ route('order.history') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-on-surface hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-secondary">receipt_long</span>
                                        <span>Riwayat Pesanan</span>
                                    </a>

                                    <a href="{{ route('customer.profile') }}?modal=profile" class="flex items-center gap-2.5 px-4 py-2 text-xs text-on-surface hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-outline">manage_accounts</span>
                                        <span>Edit Profil</span>
                                    </a>

                                    <a href="{{ route('customer.profile') }}?modal=password" class="flex items-center gap-2.5 px-4 py-2 text-xs text-on-surface hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-outline">lock_reset</span>
                                        <span>Ganti Kata Sandi</span>
                                    </a>
                                @else
                                    <!-- Menu Staf (Kasir / Admin) -->
                                    <div class="px-4 py-1 text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">
                                        Operasional Kedai
                                    </div>
                                    @if(auth()->user()->isKasir())
                                        <a href="{{ route('kasir.antrean') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-secondary">receipt_long</span>
                                            <span>Antrean Pesanan Masuk</span>
                                        </a>
                                        <a href="{{ route('kasir.pos') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-primary">point_of_sale</span>
                                            <span>POS / Walk-in</span>
                                        </a>
                                        <a href="{{ route('kasir.rekap') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-outline">calculate</span>
                                            <span>Rekap Shift</span>
                                        </a>
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                        <a href="/admin/dashboard" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-primary hover:bg-primary-container/20 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                                            <span>Panel Admin</span>
                                        </a>
                                    @endif
                                @endif
                            </div>

                            <!-- Logout Option -->
                            <div class="border-t border-outline-variant/20 pt-1 mt-1">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-error font-semibold hover:bg-error-container/40 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[18px]">logout</span>
                                        <span>Keluar / Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="/login" class="font-semibold text-xs px-4 py-2 rounded-full border border-primary/20 text-primary hover:bg-surface-container transition-all">
                            Masuk
                        </a>
                        <a href="/register" class="font-semibold text-xs px-4 py-2 rounded-full bg-primary text-on-primary hover:bg-primary-container transition-all">
                            Daftar
                        </a>
                    </div>
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
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-lg border-t border-outline-variant/30 px-3 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[20px]">home</span>
            <span class="text-[11px]">Beranda</span>
        </a>
        <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('catalog.*') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[20px]">restaurant_menu</span>
            <span class="text-[11px]">Katalog</span>
        </a>
        <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('cart.*') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
            <span class="text-[11px]">Keranjang</span>
            @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 px-1.5 py-0.2 rounded-full bg-secondary text-white text-[9px] flex items-center justify-center font-bold">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('about') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('about') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[20px]">auto_stories</span>
            <span class="text-[11px]">Tentang</span>
        </a>
        <a href="{{ route('contact') }}" class="flex flex-col items-center gap-0.5 text-xs {{ request()->routeIs('contact') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[20px]">call</span>
            <span class="text-[11px]">Kontak</span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-surface-container-highest/60 border-t border-outline-variant/30 pt-12 pb-24 md:pb-12 mt-16 text-on-surface-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="JelangPulang Coffee" class="h-9 w-auto object-contain">
                </div>
                <p class="text-sm italic">"Satu gelas sebelum arah pulang."</p>
                <p class="text-xs leading-relaxed">Ritual transisi sore hari, melepas hiruk-pikuk dalam secangkir seduhan kopi artisanal hangat.</p>
            </div>

            <div>
                <h4 class="font-headline text-lg font-bold text-primary mb-3 uppercase tracking-wider">Kunjungi Kedai</h4>
                <p class="text-sm leading-relaxed">Jl. Senja Kencana No. 42, Kebayoran Baru, Jakarta Selatan</p>
                <div class="pt-2">
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider">Jam Operasional</p>
                    <p class="text-sm">Senin – Minggu: 15:00 – 22:00 WIB</p>
                </div>
            </div>

            <div>
                <h4 class="font-headline text-lg font-bold text-primary mb-3 uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-primary transition-colors">Katalog Menu</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Hubungi Kami</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-headline text-lg font-bold text-primary mb-3 uppercase tracking-wider">Kontak & Sosial</h4>
                <p class="text-sm">+62 812-3456-7890</p>
                <p class="text-sm">halo@jelangpulang.id</p>
                <div class="flex items-center gap-2 pt-3">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="w-9 h-9 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><rect height="20" rx="5" ry="5" width="20" x="2" y="2"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="w-9 h-9 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"></path></svg>
                    </a>
                    <a href="mailto:halo@jelangpulang.id" aria-label="Email" class="w-9 h-9 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><rect height="16" rx="2" width="20" x="2" y="4"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                    </a>
                </div>
                <p class="text-xs text-on-surface-variant/70 mt-4">
                    &copy; {{ date('Y') }} Kedai Kopi JelangPulang. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
