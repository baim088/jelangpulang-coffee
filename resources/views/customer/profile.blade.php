<x-customer-layout>
    <x-slot:title>Dashboard Akun Saya — JelangPulang</x-slot:title>

    <div x-data="{ 
        editProfileModal: {{ ($errors->has('name') || $errors->has('email') || $errors->has('phone') || request('modal') === 'profile') ? 'true' : 'false' }},
        editPasswordModal: {{ ($errors->has('current_password') || $errors->has('password') || request('modal') === 'password') ? 'true' : 'false' }}
    }" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

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

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="editProfileModal = true" class="px-4 py-2.5 rounded-full bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-bold font-headline flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                    <span>Edit Profil</span>
                </button>
                <button type="button" @click="editPasswordModal = true" class="px-4 py-2.5 rounded-full bg-surface-container-highest text-primary hover:bg-primary hover:text-on-primary transition-all text-xs font-bold font-headline flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                    <span>Ganti Kata Sandi</span>
                </button>
                <a href="{{ route('order.history') }}" class="px-4 py-2.5 rounded-full bg-secondary-container text-on-secondary-container hover:opacity-90 transition-all text-xs font-bold font-headline flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                    <span>Riwayat Pesanan</span>
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

        <!-- ==============================================
             MODAL 1: EDIT INFORMASI AKUN
             ============================================== -->
        <div x-cloak x-show="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-outline-variant/40 space-y-5" @click.away="editProfileModal = false">
                <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                    <div class="flex items-center gap-2 text-primary">
                        <span class="material-symbols-outlined text-[24px]">manage_accounts</span>
                        <h3 class="font-headline text-xl font-bold">Edit Informasi Akun</h3>
                    </div>
                    <button type="button" @click="editProfileModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_name">
                            Nama Lengkap <span class="text-error">*</span>
                        </label>
                        <input type="text" id="modal_name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('name')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_email">
                            Alamat Email <span class="text-error">*</span>
                        </label>
                        <input type="email" id="modal_email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('email')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_phone">
                            Nomor WhatsApp / Telepon
                        </label>
                        <input type="text" id="modal_phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('phone')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-outline-variant/20">
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
             MODAL 2: GANTI KATA SANDI
             ============================================== -->
        <div x-cloak x-show="editPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-outline-variant/40 space-y-5" @click.away="editPasswordModal = false">
                <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                    <div class="flex items-center gap-2 text-secondary">
                        <span class="material-symbols-outlined text-[24px]">lock_reset</span>
                        <h3 class="font-headline text-xl font-bold">Ganti Kata Sandi</h3>
                    </div>
                    <button type="button" @click="editPasswordModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_current_password">
                            Kata Sandi Saat Ini <span class="text-error">*</span>
                        </label>
                        <input type="password" id="modal_current_password" name="current_password" required
                               placeholder="Masukkan kata sandi lama Anda"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('current_password')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_password">
                            Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <input type="password" id="modal_password" name="password" required
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        @error('password')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1" for="modal_password_confirmation">
                            Konfirmasi Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <input type="password" id="modal_password_confirmation" name="password_confirmation" required
                               placeholder="Ulangi kata sandi baru"
                               class="w-full px-4 py-2.5 rounded-2xl bg-surface-container text-xs font-medium text-on-surface border border-outline-variant/30 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-outline-variant/20">
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

    </div>
</x-customer-layout>
