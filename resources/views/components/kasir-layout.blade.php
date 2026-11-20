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
<body class="bg-background text-on-surface antialiased flex min-h-screen"
      x-data="{ 
          editProfileModal: {{ ($errors->has('name') || $errors->has('email') || $errors->has('phone')) ? 'true' : 'false' }}, 
          editPasswordModal: {{ ($errors->has('current_password') || $errors->has('password')) ? 'true' : 'false' }},
          profileDropdown: false 
      }">

    <!-- ASIDE / SIDEBAR KASIR -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-surface-container-low z-50 flex flex-col justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] border-r border-surface-container-highest/60">
        <div class="flex flex-col">
            <!-- Brand & Status Operasional -->
            <div class="p-5 flex flex-col gap-2 bg-surface-container-low border-b border-surface-container-highest/40">
                <a href="{{ route('kasir.antrean') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="JelangPulang" class="h-9 w-auto object-contain">
                </a>
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

            <!-- Tombol Aksi Profil & Password -->
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="editProfileModal = true" class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded-xl bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-semibold">
                    <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                    <span>Edit Profil</span>
                </button>
                <button type="button" @click="editPasswordModal = true" class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded-xl bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-semibold">
                    <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                    <span>Ganti Sandi</span>
                </button>
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

            <div class="flex items-center gap-4 sm:gap-6">
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

                <!-- Dropdown Profil Header Kasir -->
                <div class="relative" @click.away="profileDropdown = false">
                    <button @click="profileDropdown = !profileDropdown" type="button" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full hover:bg-surface-container-high transition-colors">
                        <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                        </div>
                        <span class="text-xs font-semibold text-on-surface hidden md:inline-block max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">expand_more</span>
                    </button>

                    <div x-cloak x-show="profileDropdown" class="absolute right-0 mt-2 w-56 rounded-2xl bg-surface-container-lowest shadow-xl border border-surface-container-highest/80 py-2 z-50">
                        <div class="px-4 py-2 border-b border-surface-container-highest/50">
                            <p class="text-xs font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-secondary-container text-on-secondary-container">
                                Role: {{ auth()->user()->role }}
                            </span>
                        </div>
                        <button type="button" @click="editProfileModal = true; profileDropdown = false" class="w-full text-left px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-high flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">manage_accounts</span>
                            <span>Edit Profil</span>
                        </button>
                        <button type="button" @click="editPasswordModal = true; profileDropdown = false" class="w-full text-left px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-high flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-secondary">lock_reset</span>
                            <span>Ganti Kata Sandi</span>
                        </button>
                        <div class="border-t border-surface-container-highest/50 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-error hover:bg-error-container/40 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Keluar / Ganti Sesi</span>
                            </button>
                        </form>
                    </div>
                </div>
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

    <!-- ==============================================
         MODAL 1: EDIT PROFIL KASIR
         ============================================== -->
    <div x-cloak x-show="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-surface-container-highest/80 space-y-5" @click.away="editProfileModal = false">
            <div class="flex items-center justify-between border-b border-surface-container-highest/60 pb-3">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-[24px]">manage_accounts</span>
                    <h3 class="font-headline text-xl font-bold">Edit Informasi Profil Kasir</h3>
                </div>
                <button type="button" @click="editProfileModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_modal_name">
                        Nama Lengkap <span class="text-error">*</span>
                    </label>
                    <input type="text" id="kasir_modal_name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    @error('name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_modal_email">
                        Alamat Email <span class="text-error">*</span>
                    </label>
                    <input type="email" id="kasir_modal_email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    @error('email')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_modal_phone">
                        Nomor WhatsApp / Telepon
                    </label>
                    <input type="text" id="kasir_modal_phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    @error('phone')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-surface-container-highest/60">
                    <button type="button" @click="editProfileModal = false" class="px-4 py-2 rounded-full bg-surface-container text-on-surface text-xs font-semibold hover:bg-surface-container-high">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-full bg-primary text-on-primary font-headline text-xs font-bold hover:bg-primary-container transition-all shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==============================================
         MODAL 2: GANTI KATA SANDI KASIR
         ============================================== -->
    <div x-cloak x-show="editPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-surface-container-highest/80 space-y-5" @click.away="editPasswordModal = false">
            <div class="flex items-center justify-between border-b border-surface-container-highest/60 pb-3">
                <div class="flex items-center gap-2 text-secondary">
                    <span class="material-symbols-outlined text-[24px]">lock_reset</span>
                    <h3 class="font-headline text-xl font-bold">Ganti Kata Sandi Kasir</h3>
                </div>
                <button type="button" @click="editPasswordModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
            </div>

            <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_current_password">
                        Kata Sandi Saat Ini <span class="text-error">*</span>
                    </label>
                    <input type="password" id="kasir_current_password" name="current_password" required
                           placeholder="Masukkan kata sandi lama Anda"
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    @error('current_password')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_new_password">
                        Kata Sandi Baru <span class="text-error">*</span>
                    </label>
                    <input type="password" id="kasir_new_password" name="password" required
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    @error('password')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="kasir_new_password_confirmation">
                        Konfirmasi Kata Sandi Baru <span class="text-error">*</span>
                    </label>
                    <input type="password" id="kasir_new_password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi kata sandi baru"
                           class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-surface-container-highest/80 focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-surface-container-highest/60">
                    <button type="button" @click="editPasswordModal = false" class="px-4 py-2 rounded-full bg-surface-container text-on-surface text-xs font-semibold hover:bg-surface-container-high">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-full bg-secondary text-on-secondary font-headline text-xs font-bold hover:bg-secondary/90 transition-all shadow-sm">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
