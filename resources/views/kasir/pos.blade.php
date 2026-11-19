<x-kasir-layout>
    <x-slot name="title">POS Transaksi Walk-in — JelangPulang</x-slot>

    <!-- POS CONTAINER (Alpine.js state) -->
    <div x-data="posApp()" class="flex flex-col gap-6">

        @if(session('success_order'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600 text-[28px]">receipt_long</span>
                    <div>
                        <h4 class="font-headline font-bold text-base">Transaksi Berhasil Disimpan!</h4>
                        <p class="text-xs text-emerald-700">Nomor Pesanan: <strong class="text-emerald-950">{{ session('success_order') }}</strong></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('kasir.struk', session('success_order')) }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-all flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">print</span>
                        <span>Cetak Struk Thermal</span>
                    </a>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-12 gap-6 items-start">
            
            <!-- ==============================================
                 KOLOM KIRI (7 Kolom): KATALOG PRODUK CEPAT POS
                 ============================================== -->
            <div class="col-span-12 xl:col-span-7 flex flex-col gap-4">
                
                <!-- Search & Status Top Bar -->
                <div class="bg-surface-container-low rounded-2xl p-4 border border-surface-container-highest/60 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <div class="relative flex-1 w-full">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama menu atau minuman..." 
                               class="w-full pl-10 pr-4 py-2 rounded-full bg-surface-container-lowest text-xs text-on-surface border border-surface-container-highest/60 focus:ring-1 focus:ring-primary focus:outline-none">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-full bg-secondary-container/50 text-on-secondary-container text-xs font-semibold flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-secondary"></span>
                            <span>{{ $categories->sum(fn($c) => $c->products->count()) }} Menu Aktif</span>
                        </span>
                    </div>
                </div>

                <!-- Kategori Filter Chips -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 select-none">
                    <button type="button" @click="activeCategory = 'all'" 
                            :class="activeCategory === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface hover:bg-primary-fixed'"
                            class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all">
                        Semua Menu
                    </button>
                    @foreach($categories as $category)
                        <button type="button" @click="activeCategory = '{{ $category->id }}'" 
                                :class="activeCategory === '{{ $category->id }}' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface hover:bg-primary-fixed'"
                                class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all">
                            {{ $category->name }} ({{ $category->products->count() }})
                        </button>
                    @endforeach
                </div>

                <!-- Grid Kartu Menu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($categories as $category)
                        @foreach($category->products as $product)
                            <div x-show="(activeCategory === 'all' || activeCategory === '{{ $category->id }}') && ('{{ strtolower($product->name) }}'.includes(searchQuery.toLowerCase()))"
                                 class="bg-surface-container-lowest rounded-2xl p-4 border border-surface-container-highest/60 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                                
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="flex-1 min-w-0">
                                            <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">{{ $category->name }}</span>
                                            <h3 class="font-headline text-base font-bold text-primary leading-tight truncate mt-0.5" title="{{ $product->name }}">{{ $product->name }}</h3>
                                        </div>
                                        @if($product->is_featured)
                                            <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[10px] font-bold">Favorit</span>
                                        @endif
                                    </div>

                                    <p class="font-headline text-lg font-bold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>

                                <!-- Tombol Aksi Cepat POS -->
                                <div class="mt-3 pt-3 border-t border-surface-container-highest/40 flex flex-col gap-1.5">
                                    @if($product->is_hot && $product->is_ice)
                                        <div class="grid grid-cols-2 gap-1.5">
                                            <button type="button" @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, 'ice')"
                                                    class="py-1.5 px-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface transition-all text-xs font-semibold flex items-center justify-center gap-1">
                                                <span class="material-symbols-outlined text-[14px] text-cyan-600">ac_unit</span>
                                                <span>+ Es</span>
                                            </button>
                                            <button type="button" @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, 'hot')"
                                                    class="py-1.5 px-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface transition-all text-xs font-semibold flex items-center justify-center gap-1">
                                                <span class="material-symbols-outlined text-[14px] text-amber-600">local_fire_department</span>
                                                <span>+ Hangat</span>
                                            </button>
                                        </div>
                                    @elseif($product->is_ice)
                                        <button type="button" @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, 'ice')"
                                                class="w-full py-1.5 px-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface transition-all text-xs font-semibold flex items-center justify-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-cyan-600">ac_unit</span>
                                            <span>Tambah (Ice)</span>
                                        </button>
                                    @elseif($product->is_hot)
                                        <button type="button" @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, 'hot')"
                                                class="w-full py-1.5 px-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface transition-all text-xs font-semibold flex items-center justify-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-amber-600">local_fire_department</span>
                                            <span>Tambah (Hot)</span>
                                        </button>
                                    @else
                                        <button type="button" @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, null)"
                                                class="w-full py-1.5 px-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-on-primary text-on-surface transition-all text-xs font-semibold flex items-center justify-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">add</span>
                                            <span>Tambah Pesanan</span>
                                        </button>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    @endforeach
                </div>

            </div>

            <!-- ==============================================
                 KOLOM KANAN (5 Kolom): KERANJANG TRANSAKSI KASIR
                 ============================================== -->
            <div class="col-span-12 xl:col-span-5 bg-surface-container-lowest rounded-2xl p-5 border border-surface-container-highest/60 shadow-sm flex flex-col justify-between sticky top-20 min-h-[calc(100vh-8rem)]">
                
                <form id="posCheckoutForm" action="{{ route('kasir.pos.checkout') }}" method="POST" class="flex flex-col flex-1 justify-between">
                    @csrf
                    
                    <div class="flex flex-col gap-4">
                        <!-- Header Keranjang & Opsi Meja -->
                        <div class="border-b border-surface-container-highest/40 pb-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[20px]">shopping_cart</span>
                                    <h2 class="font-headline text-lg font-bold text-primary">Pesanan Baru POS</h2>
                                </div>
                                <button type="button" @click="clearCart()" class="text-xs text-error hover:underline" x-show="cart.length > 0">
                                    Kosongkan
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-on-surface-variant mb-1">Nama Pemesan</label>
                                    <input type="text" name="customer_name" x-model="customerName" placeholder="Pelanggan Walk-in" 
                                           class="w-full px-3 py-1.5 rounded-xl bg-surface-container-low text-xs border border-surface-container-highest/60 focus:outline-none focus:ring-1 focus:ring-primary">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-on-surface-variant mb-1">Tipe Pesanan / Meja</label>
                                    <div class="flex gap-2">
                                        <select x-model="orderType" class="w-1/2 px-2 py-1.5 rounded-xl bg-surface-container-low text-xs border border-surface-container-highest/60 focus:outline-none">
                                            <option value="takeaway">Takeaway</option>
                                            <option value="dine_in">Dine-in</option>
                                        </select>
                                        <input type="text" name="table_no" x-model="tableNo" x-show="orderType === 'dine_in'" placeholder="No. Meja"
                                               class="w-1/2 px-3 py-1.5 rounded-xl bg-surface-container-low text-xs border border-surface-container-highest/60 focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Item Belanja di Keranjang -->
                        <div class="flex flex-col gap-3 max-h-[40vh] overflow-y-auto pr-1">
                            <template x-if="cart.length === 0">
                                <div class="py-12 text-center text-on-surface-variant flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[40px] text-surface-container-highest mb-1">point_of_sale</span>
                                    <p class="text-xs">Belum ada menu yang dipilih.</p>
                                    <p class="text-[11px] text-outline">Klik menu di sebelah kiri untuk memasukkan ke keranjang.</p>
                                </div>
                            </template>

                            <template x-for="(item, index) in cart" :key="index">
                                <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container-highest/40 flex flex-col gap-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-headline font-bold text-sm text-primary truncate" x-text="item.name"></h4>
                                            <span class="text-[11px] text-on-surface-variant capitalize" x-show="item.variant" x-text="'Varian: ' + item.variant"></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-headline font-bold text-sm text-on-surface" x-text="formatRupiah(item.price * item.qty)"></span>
                                            <button type="button" @click="removeItem(index)" class="text-error hover:text-error/80 text-xs ml-2">&times;</button>
                                        </div>
                                    </div>

                                    <!-- Stepper & Catatan Barista -->
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-surface-container-highest/40">
                                        <div class="flex items-center border border-surface-container-highest rounded-lg bg-surface-container-lowest">
                                            <button type="button" @click="decreaseQty(index)" class="px-2 py-0.5 text-xs font-bold hover:bg-surface-container-high">-</button>
                                            <span class="px-2 py-0.5 text-xs font-bold" x-text="item.qty"></span>
                                            <button type="button" @click="increaseQty(index)" class="px-2 py-0.5 text-xs font-bold hover:bg-surface-container-high">+</button>
                                        </div>

                                        <input type="text" x-model="item.note" placeholder="Catatan barista (opsional)..." 
                                               class="flex-1 px-2 py-1 rounded-lg bg-surface-container-lowest text-[11px] border border-surface-container-highest/40 focus:outline-none">
                                    </div>

                                    <!-- Hidden Inputs untuk submit form -->
                                    <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                                    <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
                                    <input type="hidden" :name="'items[' + index + '][variant]'" :value="item.variant">
                                    <input type="hidden" :name="'items[' + index + '][note]'" :value="item.note">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Ringkasan Tagihan & Tombol Bayar -->
                    <div class="pt-4 border-t border-surface-container-highest/60 flex flex-col gap-3 mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-on-surface-variant">Total Tagihan:</span>
                            <span class="font-headline text-2xl font-bold text-primary" x-text="formatRupiah(totalCart)"></span>
                        </div>

                        <!-- Hidden payment inputs -->
                        <input type="hidden" name="payment_method" x-model="paymentMethod">
                        <input type="hidden" name="cash_received" x-model="cashReceived">

                        <div class="grid grid-cols-2 gap-2" x-show="cart.length > 0">
                            <!-- Tombol Bayar Tunai -->
                            <button type="button" @click="openCashModal()" 
                                    class="py-3 px-3 rounded-xl bg-secondary text-on-secondary font-bold text-xs hover:bg-secondary/90 transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">payments</span>
                                <span>Bayar Tunai</span>
                            </button>

                            <!-- Tombol Bayar QRIS -->
                            <button type="button" @click="processQris()" 
                                    class="py-3 px-3 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                                <span>Bayar QRIS</span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

        </div>

        <!-- ==============================================
             MODAL PEMBAYARAN TUNAI (CASH) (FR-C05)
             ============================================== -->
        <div x-cloak x-show="cashModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface rounded-2xl max-w-md w-full p-6 shadow-2xl border border-surface-container-highest/60 flex flex-col gap-4" @click.away="cashModal = false">
                <div class="flex items-center justify-between border-b border-surface-container-highest/40 pb-3">
                    <div class="flex items-center gap-2 text-secondary">
                        <span class="material-symbols-outlined text-[24px]">point_of_sale</span>
                        <h3 class="font-headline text-xl font-bold">Pembayaran Tunai</h3>
                    </div>
                    <button type="button" @click="cashModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
                </div>

                <div class="bg-surface-container-low p-4 rounded-xl flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">Total Tagihan:</span>
                    <span class="font-headline text-2xl font-bold text-primary" x-text="formatRupiah(totalCart)"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface mb-1">Uang Tunai Diterima (Rp)</label>
                    <input type="number" x-model.number="cashReceived" placeholder="0" 
                           class="w-full p-3 rounded-xl bg-surface-container-lowest text-lg font-bold text-primary border border-surface-container-highest focus:ring-2 focus:ring-primary focus:outline-none">
                </div>

                <!-- Pecahan Cepat -->
                <div>
                    <span class="text-[11px] text-on-surface-variant font-medium block mb-1.5">Pecahan Cepat:</span>
                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" @click="cashReceived = totalCart" class="py-1.5 px-2 rounded-lg bg-surface-container-high text-xs font-semibold hover:bg-surface-container-highest">Uang Pas</button>
                        <button type="button" @click="cashReceived = 20000" class="py-1.5 px-2 rounded-lg bg-surface-container-high text-xs font-semibold hover:bg-surface-container-highest">20.000</button>
                        <button type="button" @click="cashReceived = 50000" class="py-1.5 px-2 rounded-lg bg-surface-container-high text-xs font-semibold hover:bg-surface-container-highest">50.000</button>
                        <button type="button" @click="cashReceived = 100000" class="py-1.5 px-2 rounded-lg bg-surface-container-high text-xs font-semibold hover:bg-surface-container-highest">100.000</button>
                    </div>
                </div>

                <!-- Kembalian Display -->
                <div class="p-4 rounded-xl border" :class="changeAmount >= 0 ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900'">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold" x-text="changeAmount >= 0 ? 'Kembalian:' : 'Uang Kurang:'"></span>
                        <span class="font-headline text-2xl font-bold" x-text="formatRupiah(Math.abs(changeAmount))"></span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-surface-container-highest/40">
                    <button type="button" @click="cashModal = false" class="px-4 py-2 rounded-xl bg-surface-container text-on-surface text-xs font-semibold hover:bg-surface-container-high">
                        Batal
                    </button>
                    <button type="button" @click="submitCashPayment()" :disabled="changeAmount < 0" 
                            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Konfirmasi & Selesaikan
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function posApp() {
            return {
                activeCategory: 'all',
                searchQuery: '',
                customerName: 'Pelanggan Walk-in',
                orderType: 'takeaway',
                tableNo: '',
                cart: [],
                cashModal: false,
                paymentMethod: 'cash',
                cashReceived: 0,

                addItem(id, name, price, variant) {
                    const existingIndex = this.cart.findIndex(i => i.id === id && i.variant === variant);
                    if (existingIndex > -1) {
                        this.cart[existingIndex].qty += 1;
                    } else {
                        this.cart.push({
                            id: id,
                            name: name,
                            price: price,
                            variant: variant,
                            qty: 1,
                            note: ''
                        });
                    }
                },

                increaseQty(index) {
                    this.cart[index].qty += 1;
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty -= 1;
                    } else {
                        this.removeItem(index);
                    }
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    this.cart = [];
                },

                get totalCart() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get changeAmount() {
                    return (this.cashReceived || 0) - this.totalCart;
                },

                formatRupiah(val) {
                    return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                },

                openCashModal() {
                    if (this.cart.length === 0) return;
                    this.paymentMethod = 'cash';
                    this.cashReceived = this.totalCart; // Default uang pas
                    this.cashModal = true;
                },

                submitCashPayment() {
                    if (this.changeAmount < 0) return;
                    document.getElementById('posCheckoutForm').submit();
                },

                processQris() {
                    if (this.cart.length === 0) return;
                    if (confirm('Konfirmasi pembayaran via QRIS Toko? Kasir memastikan dana sudah masuk ke ShopeePay.')) {
                        this.paymentMethod = 'qris';
                        this.cashReceived = null;
                        document.getElementById('posCheckoutForm').submit();
                    }
                }
            };
        }
    </script>
    @endpush
</x-kasir-layout>
