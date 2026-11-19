<x-customer-layout>
    <x-slot:title>Konfirmasi Pesanan — JelangPulang</x-slot:title>

    <div x-data="{ orderType: 'dinein', paymentMethod: 'qris' }" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumb / Step Indicator -->
        <div class="mb-6 pb-4 border-b border-outline-variant/30 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold tracking-widest text-secondary uppercase">LANGKAH AKHIR</span>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-primary tracking-tight uppercase leading-none">
                    Konfirmasi & Pembayaran
                </h1>
            </div>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Ubah Keranjang</span>
            </a>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <!-- Left Column: Form Input -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. Tipe Layanan & Data Pemesan -->
                <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                        <span class="material-symbols-outlined text-primary text-[22px]">badge</span>
                        <h2 class="font-headline text-xl font-bold text-primary">Informasi Pemesan</h2>
                    </div>

                    <!-- Dine-In vs Takeaway Toggle -->
                    <div class="grid grid-cols-2 gap-2 p-1 bg-surface-container rounded-full">
                        <button type="button" @click="orderType = 'dinein'" 
                            :class="orderType === 'dinein' ? 'bg-primary text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                            class="py-2.5 rounded-full text-xs font-headline flex items-center justify-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-[16px]">table_restaurant</span>
                            <span>Dine-in (Di Tempat)</span>
                        </button>
                        <button type="button" @click="orderType = 'takeaway'" 
                            :class="orderType === 'takeaway' ? 'bg-primary text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:text-on-surface'"
                            class="py-2.5 rounded-full text-xs font-headline flex items-center justify-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                            <span>Bawa Pulang (Takeaway)</span>
                        </button>
                    </div>
                    <input type="hidden" name="order_type" :value="orderType">

                    <!-- Customer Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="customer_name">
                            Nama Pemesan <span class="text-error">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name', auth()->user()?->name) }}"
                            placeholder="Contoh: Dimas, Sari..." 
                            class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                        @error('customer_name')
                            <p class="text-error text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Table Number (Shown if Dine-in) -->
                    <div x-show="orderType === 'dinein'">
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="table_no">
                            Nomor Meja <span class="text-xs font-normal text-outline">(Lihat stiker nomor di meja kedai)</span>
                        </label>
                        <input type="text" id="table_no" name="table_no" value="{{ old('table_no') }}"
                            placeholder="Contoh: Meja 04..." 
                            class="w-full px-4 py-3 rounded-2xl bg-surface-container text-sm font-medium text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/40 border border-outline-variant/30">
                    </div>
                </div>

                <!-- 2. Pilihan Metode Pembayaran -->
                <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-outline-variant/20">
                        <span class="material-symbols-outlined text-primary text-[22px]">payments</span>
                        <h2 class="font-headline text-xl font-bold text-primary">Metode Pembayaran</h2>
                    </div>

                    <div class="space-y-3">
                        <!-- QRIS Option -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all"
                               :class="paymentMethod === 'qris' ? 'border-primary bg-primary/5 shadow-sm' : 'border-outline-variant/30 bg-surface'">
                            <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="mt-1 text-primary focus:ring-primary">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-headline text-lg font-bold text-primary">QRIS ShopeePay Toko</span>
                                    <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-[10px] font-bold">Rekomendasi</span>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    Scan kode QR di ponsel dan unggah bukti transfer. Pesanan langsung diverifikasi kasir tanpa antre di meja kasir.
                                </p>
                            </div>
                        </label>

                        <!-- Bayar di Kasir Option -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all"
                               :class="paymentMethod === 'pay_at_counter' ? 'border-primary bg-primary/5 shadow-sm' : 'border-outline-variant/30 bg-surface'">
                            <input type="radio" name="payment_method" value="pay_at_counter" x-model="paymentMethod" class="mt-1 text-primary focus:ring-primary">
                            <div class="flex-1">
                                <span class="font-headline text-lg font-bold text-primary">Bayar di Kasir (Tunai)</span>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    Pesan sekarang dari HP, lalu sebutkan nomor pesanan Anda ke meja kasir untuk membayar tunai.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary & Confirm Button -->
            <div class="lg:col-span-5 bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 sticky top-28 space-y-4">
                <h3 class="font-headline text-2xl font-bold text-primary pb-2 border-b border-outline-variant/20">
                    Detail Pesanan
                </h3>

                <!-- Mini Item List -->
                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                    @foreach($items as $it)
                        <div class="flex items-start justify-between text-xs">
                            <div class="flex-1 pr-2">
                                <p class="font-bold text-on-surface">{{ $it['qty'] }}x {{ $it['product_name'] }} ({{ strtoupper($it['variant']) }})</p>
                                @if(!empty($it['add_ons']))
                                    <p class="text-[10px] text-secondary">
                                        + {{ implode(', ', array_column($it['add_ons'], 'name')) }}
                                    </p>
                                @endif
                            </div>
                            <span class="font-price font-bold text-on-surface">Rp {{ number_format($it['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-outline-variant/20 space-y-2 text-sm">
                    <div class="flex items-center justify-between text-on-surface-variant">
                        <span>Total Tagihan</span>
                        <span class="font-price text-3xl font-extrabold text-secondary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-6 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all shadow-md flex items-center justify-center gap-2 active:scale-95 text-center mt-4">
                    <span>Konfirmasi & Buat Pesanan</span>
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </button>

                <p class="text-[11px] text-center text-on-surface-variant">
                    Dengan membuat pesanan, stok bahan akan disiapkan barista setelah pembayaran terkonfirmasi.
                </p>
            </div>

        </form>

    </div>
</x-customer-layout>
