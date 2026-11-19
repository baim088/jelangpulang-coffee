<x-customer-layout>
    <x-slot:title>Dashboard Akun Saya — JelangPulang</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

        <!-- HEADER PROFIL PELANGGAN -->
        <div class="bg-surface-container rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary text-on-primary flex items-center justify-center font-headline font-bold text-3xl sm:text-4xl shadow-md border-2 border-surface-container-lowest">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h1 class="font-headline text-2xl sm:text-3xl font-bold text-primary">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-[11px] font-bold font-headline">
                            Pelanggan JelangPulang
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant flex flex-wrap items-center gap-3">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[15px]">mail</span>{{ $user->email }}</span>
                        @if($user->phone)
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[15px]">call</span>{{ $user->phone }}</span>
                        @endif
                        <span class="text-outline-variant">•</span>
                        <span>Bergabung sejak {{ $user->created_at->isoFormat('MMMM Y') }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('order.history') }}" class="px-5 py-2.5 rounded-full bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-bold font-headline flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">history</span>
                    <span>Semua Riwayat</span>
                </a>
                <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-full bg-primary text-on-primary hover:bg-primary-container transition-all text-xs font-bold font-headline flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">coffee</span>
                    <span>Pesan Kopi</span>
                </a>
            </div>
        </div>

        <!-- PESANAN AKTIF ALERT (JIKA ADA) -->
        @if($activeOrders->isNotEmpty())
            <div class="bg-secondary-fixed/50 border border-secondary/30 rounded-3xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-on-secondary-container font-headline font-bold text-base">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
                        <span>Pesanan Aktif Sedang Berjalan ({{ $activeOrders->count() }})</span>
                    </div>
                    <span class="text-xs text-on-secondary-container">Real-time status</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($activeOrders as $act)
                        <div class="bg-surface-container-lowest rounded-2xl p-4 border border-outline-variant/30 flex items-center justify-between">
                            <div>
                                <span class="font-headline font-bold text-primary text-base">{{ $act->order_no }}</span>
                                <p class="text-xs text-on-surface-variant">
                                    {{ $act->created_at->diffForHumans() }} • Rp {{ number_format($act->total, 0, ',', '.') }}
                                </p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $act->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $act->status === 'diproses' ? 'Sedang Diracik Barista' : 'Menunggu Verifikasi' }}
                                </span>
                            </div>
                            <a href="{{ route('order.status', $act->order_no) }}" class="px-3.5 py-1.5 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all">
                                Pantau
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4 BENTO STATISTIK BELANJA PELANGGAN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Transaksi (Rp) -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Total Belanja</span>
                    <span class="material-symbols-outlined text-secondary text-[22px]">payments</span>
                </div>
                <div class="mt-4">
                    <span class="font-headline text-3xl font-extrabold text-primary block leading-none">
                        Rp {{ number_format($totalSpending, 0, ',', '.') }}
                    </span>
                    <p class="text-[11px] text-on-surface-variant mt-1.5">Sepanjang waktu di JelangPulang</p>
                </div>
            </div>

            <!-- Jumlah Pesanan -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Jumlah Pesanan</span>
                    <span class="material-symbols-outlined text-primary text-[22px]">receipt_long</span>
                </div>
                <div class="mt-4">
                    <span class="font-headline text-3xl font-extrabold text-primary block leading-none">
                        {{ $totalOrders }} Kali
                    </span>
                    <p class="text-[11px] text-on-surface-variant mt-1.5">{{ $completedOrders }} pesanan selesai dinikmati</p>
                </div>
            </div>

            <!-- Menu Favorit -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Menu Favorit Anda</span>
                    <span class="material-symbols-outlined text-amber-700 text-[22px]">favorite</span>
                </div>
                <div class="mt-4">
                    <span class="font-headline text-xl font-bold text-primary block leading-tight truncate">
                        {{ $favoriteItem->product_name ?? 'Belum ada data' }}
                    </span>
                    <p class="text-[11px] text-on-surface-variant mt-1.5">
                        @if($favoriteItem)
                            Telah dipesan {{ $favoriteItem->total_qty }} porsi
                        @else
                            Mulai pesan kopi pertama Anda
                        @endif
                    </p>
                </div>
            </div>

            <!-- Status Akun -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Status Akun</span>
                    <span class="material-symbols-outlined text-emerald-600 text-[22px]">verified</span>
                </div>
                <div class="mt-4">
                    <span class="font-headline text-2xl font-bold text-emerald-700 block leading-tight">
                        Aktif Terverifikasi
                    </span>
                    <p class="text-[11px] text-on-surface-variant mt-1.5">Akses penuh self-order & riwayat</p>
                </div>
            </div>
        </div>

        <!-- 2 KOLOM: FORM EDIT PROFIL & GANTI PASSWORD -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- FORM 1: EDIT INFORMASI AKUN -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div>
                    <h2 class="font-headline text-2xl font-bold text-primary">Informasi Akun Pelanggan</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Perbarui nama lengkap, alamat email, atau nomor WhatsApp aktif Anda.</p>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="name">
                            Nama Lengkap <span class="text-error">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('name')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="email">
                            Alamat Email <span class="text-error">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('email')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="phone">
                            Nomor WhatsApp / Telepon
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('phone')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-full bg-primary text-on-primary font-headline text-sm font-bold hover:bg-primary-container transition-all shadow-sm">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- FORM 2: GANTI KATA SANDI -->
            <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div>
                    <h2 class="font-headline text-2xl font-bold text-primary">Ganti Kata Sandi</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Gunakan kombinasi kata sandi yang aman untuk melindungi akun JelangPulang Anda.</p>
                </div>

                <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="current_password">
                            Kata Sandi Saat Ini <span class="text-error">*</span>
                        </label>
                        <input type="password" id="current_password" name="current_password" required
                               placeholder="Masukkan kata sandi lama Anda"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('current_password')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="password">
                            Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('password')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="password_confirmation">
                            Konfirmasi Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="Ulangi kata sandi baru"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-full bg-secondary text-on-secondary font-headline text-sm font-bold hover:bg-secondary/90 transition-all shadow-sm">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- 3 PESANAN TERAKHIR SINGKAT -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-headline text-xl font-bold text-primary">Pesanan Terakhir Anda</h3>
                    <p class="text-xs text-on-surface-variant">Tiga transaksi terbaru yang Anda lakukan di kedai.</p>
                </div>
                <a href="{{ route('order.history') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="py-8 text-center text-on-surface-variant text-xs">
                    Belum ada riwayat pesanan. Nikmati seduhan kopi senja pertama Anda hari ini!
                </div>
            @else
                <div class="divide-y divide-outline-variant/20">
                    @foreach($recentOrders as $rec)
                        <div class="py-3 flex items-center justify-between gap-4 text-xs">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-headline font-bold text-primary text-sm">{{ $rec->order_no }}</span>
                                    <span class="text-on-surface-variant">• {{ $rec->created_at->isoFormat('D MMM Y, HH:mm') }} WIB</span>
                                </div>
                                <p class="text-on-surface-variant mt-0.5">
                                    {{ $rec->items->pluck('product_name')->implode(', ') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="font-headline font-bold text-sm text-secondary block">Rp {{ number_format($rec->total, 0, ',', '.') }}</span>
                                <a href="{{ route('order.status', $rec->order_no) }}" class="text-[11px] text-primary hover:underline font-semibold">
                                    Detail Pesanan &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-customer-layout>
