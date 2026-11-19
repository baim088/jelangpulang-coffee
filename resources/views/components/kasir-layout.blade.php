<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $title ?? 'Kasir JelangPulang' }}</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Fira+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS (CDN dengan tema JelangPulang) -->
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
                        "primary-fixed": "#ffdbcd",
                        secondary: "#89511c",
                        "secondary-container": "#ffb376",
                        "on-secondary-container": "#79430e",
                        background: "#fdf9f4",
                        surface: "#fdf9f4",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f7f3ee",
                        "surface-container": "#f1ede8",
                        "surface-container-high": "#ebe8e3",
                        "surface-container-highest": "#e6e2dd",
                        "on-surface": "#1c1c19",
                        "on-surface-variant": "#53433d",
                        error: "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        outline: "#86736c",
                    },
                    fontFamily: {
                        headline: ["Barlow Condensed", "sans-serif"],
                        body: ["Fira Sans", "sans-serif"],
                    }
                }
            }
        };
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Fira Sans', sans-serif; }
        .font-headline { font-family: 'Barlow Condensed', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased flex min-h-screen">

    <!-- ASIDE / SIDEBAR KASIR -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-surface-container-low z-50 flex flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] border-r border-surface-container-highest/60">
        <div class="flex flex-col">
            <!-- Brand & Status Operasional -->
            <div class="p-6 flex flex-col gap-2 bg-surface-container-low border-b border-surface-container-highest/40">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm">JP</span>
                    <span class="font-headline text-2xl font-bold tracking-tight uppercase text-primary">JelangPulang</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/60 text-on-secondary-container text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>Buka • Kasir Aktif
                    </span>
                    <span class="text-xs text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">schedule</span> 15:00 - 22:30
                    </span>
                </div>
            </div>

            <!-- Menu Operasional -->
            <div class="px-4 py-4">
                <p class="px-3 mb-2 text-xs uppercase font-bold tracking-wider text-on-surface-variant">Menu Operasional</p>
                <nav class="flex flex-col gap-1">
                    <a href="{{ route('kasir.antrean') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all font-medium text-sm {{ request()->routeIs('kasir.antrean*') ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                            <span>Antrean Pesanan</span>
                        </div>
                        @php
                            $waitingCount = \App\Models\Order::where('status', 'menunggu_verifikasi')->count();
                        @endphp
                        @if($waitingCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ request()->routeIs('kasir.antrean*') ? 'bg-error text-on-error' : 'bg-error/20 text-error' }}">
                                {{ $waitingCount }} Baru
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('kasir.pos') }}" 
                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all font-medium text-sm {{ request()->routeIs('kasir.pos*') ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">point_of_sale</span>
                        <span>POS / Walk-in</span>
                    </a>

                    <a href="{{ route('kasir.rekap') }}" 
                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all font-medium text-sm {{ request()->routeIs('kasir.rekap*') ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                        <span class="material-symbols-outlined text-[20px]">calculate</span>
                        <span>Rekap Shift</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                        <div class="pt-4 mt-2 border-t border-surface-container-highest/60">
                            <p class="px-3 mb-2 text-xs uppercase font-bold tracking-wider text-on-surface-variant">Akses Admin</p>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all font-medium text-sm text-secondary hover:bg-secondary-container/30">
                                <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                                <span>Panel Admin</span>
                            </a>
                        </div>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Footer Sidebar (User Info & Logout) -->
        <div class="p-4 bg-surface-container/60 border-t border-surface-container-highest/40 flex flex-col gap-3">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                </div>
                <div class="flex flex-col flex-1 min-w-0">
                    <span class="font-semibold text-sm text-on-surface truncate">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-on-surface-variant capitalize">{{ auth()->user()->role }} • JelangPulang</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full py-2 rounded-xl bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-semibold">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Keluar / Ganti Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="pl-72 flex flex-col flex-1 min-h-screen">
        <!-- TOP HEADER -->
        <header class="h-16 bg-surface/90 backdrop-blur-xl border-b border-surface-container-highest/60 px-8 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high text-xs font-semibold text-on-surface">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Live POS & Antrean Meja</span>
                </div>
                <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container text-xs text-on-surface-variant">
                    <span class="material-symbols-outlined text-[16px]">print</span>
                    <span>Printer Thermal 58mm Siap</span>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-primary" id="live-clock">
                        {{ now()->isoFormat('dddd, D MMM Y') }}
                    </p>
                    <p class="text-xs text-on-surface-variant">
                        Sistem Kasir v1.0
                    </p>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant" title="Lihat Tampilan Pelanggan">
                    <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                </a>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-sm mb-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-error-container border border-error/30 text-on-error-container flex items-center justify-between text-sm shadow-sm mb-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-on-error-container hover:text-error">&times;</button>
                </div>
            @endif
        </div>

        <!-- MAIN BODY -->
        <main class="p-8 flex-1">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
