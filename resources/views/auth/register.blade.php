<x-customer-layout>
    <x-slot:title>Daftar Akun — JelangPulang</x-slot:title>

    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30">
            <!-- Header -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="JelangPulang" class="h-12 w-auto object-contain mx-auto">
                </a>
                <h1 class="font-display text-3xl font-extrabold text-primary uppercase">Buat Akun Pelanggan</h1>
                <p class="text-xs text-on-surface-variant mt-1">Simpan riwayat pesanan dan nikmati pesan cepat setiap sore.</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="/register" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="name">
                        Nama Lengkap <span class="text-error">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Contoh: Dimas Aditya" 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                    @error('name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="email">
                        Alamat Email <span class="text-error">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="Contoh: dimas@gmail.com" 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                    @error('email')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="phone">
                        Nomor WhatsApp / HP
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890" 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="password">
                        Kata Sandi (Min. 6 Karakter) <span class="text-error">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                        placeholder="Buat kata sandi..." 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                    @error('password')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="password_confirmation">
                        Konfirmasi Kata Sandi <span class="text-error">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="Ulangi kata sandi..." 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all shadow-md active:scale-95 text-center mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-outline-variant/20 text-center text-xs text-on-surface-variant">
                <span>Sudah punya akun?</span>
                <a href="/login" class="font-bold text-primary hover:underline ml-1">Masuk di sini</a>
            </div>
        </div>
    </div>
</x-customer-layout>
