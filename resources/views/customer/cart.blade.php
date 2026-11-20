<x-customer-layout>
    <x-slot:title>Keranjang Pesanan — JelangPulang</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-outline-variant/30">
            <div>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-primary tracking-tight uppercase leading-none">
                    Keranjang Saya
                </h1>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">Sajian terpilih untuk menemani jeda soremu.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-secondary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Tambah Menu Lain</span>
            </a>
        </div>

        @if(empty($items))
            <div class="text-center py-20 bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant/30">
                <span class="material-symbols-outlined text-6xl text-outline mb-2">shopping_bag</span>
                <h3 class="font-headline text-2xl font-bold text-primary">Keranjang Belanja Kosong</h3>
                <p class="text-sm text-on-surface-variant mt-1">Belum ada kopi atau camilan yang Anda pilih.</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-full bg-primary text-on-primary font-headline text-base font-bold shadow-md hover:bg-primary-container transition-all">
                    <span>Eksplorasi Menu</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Item List -->
                <div class="lg:col-span-7 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-surface-container-lowest rounded-3xl p-5 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($item['variant'] === 'ice')
                                                <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-[10px] font-bold font-headline">ICE</span>
                                            @elseif($item['variant'] === 'hot')
                                                <span class="px-2 py-0.5 rounded-full bg-primary/20 text-primary text-[10px] font-bold font-headline">HOT</span>
                                            @endif
                                            <h3 class="font-headline text-xl font-bold text-primary">{{ $item['product_name'] }}</h3>
                                        </div>

                                        <!-- Customization Attributes -->
                                        <div class="text-[11px] text-on-surface-variant space-y-0.5">
                                            @if(isset($item['options']['sweetness']))
                                                <p>Manis: <span class="font-semibold text-on-surface">{{ ucfirst($item['options']['sweetness']) }}</span></p>
                                            @endif
                                            @if(isset($item['options']['ice_level']) && $item['variant'] === 'ice')
                                                <p>Es: <span class="font-semibold text-on-surface">{{ str_replace('_', ' ', ucfirst($item['options']['ice_level'])) }}</span></p>
                                            @endif
                                            @if(!empty($item['add_ons']))
                                                <div class="pt-1">
                                                    <span class="font-bold text-secondary">Tambahan (Add-On):</span>
                                                    <ul class="list-disc list-inside text-on-surface">
                                                        @foreach($item['add_ons'] as $ao)
                                                            <li>{{ $ao['name'] }} (+Rp {{ number_format($ao['price'], 0, ',', '.') }})</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if(!empty($item['note']))
                                                <p class="italic text-primary/80 pt-1">Catatan: "{{ $item['note'] }}"</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                        <button type="submit" class="text-outline hover:text-error transition-colors p-1" title="Hapus item">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Stepper & Subtotal -->
                            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center justify-between">
                                <span class="font-price text-xl text-secondary font-bold">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>

                                <!-- Stepper Form -->
                                <div class="flex items-center gap-1 bg-surface-container rounded-full p-1 border border-outline-variant/30">
                                    <form method="POST" action="{{ route('cart.update') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                        <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
                                        <button type="submit" class="w-7 h-7 rounded-full bg-surface flex items-center justify-center font-bold text-primary hover:bg-surface-container-high transition-colors">-</button>
                                    </form>

                                    <span class="w-8 text-center font-headline text-base font-bold text-primary">{{ $item['qty'] }}</span>

                                    <form method="POST" action="{{ route('cart.update') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                        <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                        <button type="submit" class="w-7 h-7 rounded-full bg-surface flex items-center justify-center font-bold text-primary hover:bg-surface-container-high transition-colors">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Column: Summary & Checkout Button -->
                <div class="lg:col-span-5 bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 sticky top-28">
                    <h2 class="font-headline text-2xl font-bold text-primary mb-4 pb-2 border-b border-outline-variant/20">
                        Ringkasan Pembayaran
                    </h2>

                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span>Subtotal Menu</span>
                            <span class="font-price text-lg font-bold text-on-surface">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-on-surface-variant">
                            <span>Pajak & Layanan</span>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Termasuk</span>
                        </div>
                        <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-between">
                            <span class="font-headline text-xl font-bold text-primary">Total Tagihan</span>
                            <span class="font-price text-3xl font-extrabold text-secondary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin()))
                        <div class="p-3 mb-4 rounded-2xl bg-secondary-fixed text-on-secondary-container text-xs space-y-2 border border-secondary/30">
                            <p class="font-bold flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">info</span>
                                Akun Staf Operasional
                            </p>
                            <p class="text-[11px] leading-relaxed">
                                Anda login sebagai Kasir/Admin. Akun staf tidak melakukan self-order pelanggan.
                            </p>
                            <a href="{{ route('kasir.pos') }}" class="block text-center py-2 px-4 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container">
                                Buka Sistem POS Kasir
                            </a>
                        </div>
                    @else
                        <a href="{{ route('checkout.index') }}" class="w-full py-3.5 px-6 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all shadow-md flex items-center justify-center gap-2 active:scale-95 text-center">
                            <span>Lanjut ke Checkout</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>

                        <p class="text-[11px] text-center text-on-surface-variant mt-3">
                            Pembayaran via QRIS ShopeePay atau Tunai di Kasir.
                        </p>
                    @endif
                </div>

            </div>
        @endif

    </div>
</x-customer-layout>
