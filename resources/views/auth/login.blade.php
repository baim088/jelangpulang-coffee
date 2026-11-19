<x-customer-layout>
    <x-slot:title>Masuk — JelangPulang</x-slot:title>

    <div class="max-w-md mx-auto px-4 py-12">
        <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30">
            <!-- Header -->
            <div class="text-center mb-6">
                <span class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary font-headline font-bold text-2xl mx-auto mb-3 shadow-md">JP</span>
                <h1 class="font-display text-3xl font-extrabold text-primary uppercase">Senang Melihatmu Kembali</h1>
                <p class="text-xs text-on-surface-variant mt-1">Satu tegukan lagi sebelum arah pulang.</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="email">
                        Email atau Nomor WhatsApp
                    </label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Contoh: admin@jelangpulang.test / user@..." 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="password">
                        Kata Sandi
                    </label>
                    <input type="password" id="password" name="password" required
                        placeholder="Masukkan kata sandi..." 
                        class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-on-surface-variant">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary focus:ring-primary">
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-outline">Default pass: <code>password</code></span>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all shadow-md active:scale-95 text-center mt-2">
                    Masuk ke Akun
                </button>
            </form>

            <!-- Bottom Register Link -->
            <div class="mt-6 pt-4 border-t border-outline-variant/20 text-center text-xs text-on-surface-variant">
                <span>Belum punya akun?</span>
                <a href="/register" class="font-bold text-primary hover:underline ml-1">Daftar sekarang</a>
            </div>

            <!-- Demo Accounts Help Box -->
            <div class="mt-4 p-3 rounded-2xl bg-surface-container text-[11px] text-on-surface-variant space-y-1">
                <span class="font-bold text-primary block">Akun Uji Coba:</span>
                <p>• Admin: <code>admin@jelangpulang.test</code> / <code>password</code></p>
                <p>• Kasir: <code>kasir@jelangpulang.test</code> / <code>password</code></p>
                <p>• User: <code>user@jelangpulang.test</code> / <code>password</code></p>
            </div>
        </div>
    </div>
</x-customer-layout>
