<x-customer-layout>
    <x-slot:title>Katalog Menu — JelangPulang</x-slot:title>

    <div x-data="catalogApp()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Top Header & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-primary tracking-tight uppercase leading-none">
                    Katalog Seduhan & Cerita
                </h1>
                <p class="text-sm text-on-surface-variant mt-1">Pilih teman jedamu sore ini sebelum perjalanan kembali ke rumah.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('catalog.index') }}" class="relative w-full md:w-80">
                <input type="hidden" name="kategori" value="{{ $selectedCat }}">
                <span class="material-symbols-outlined absolute left-3.5 top-3 text-outline text-[20px]">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kopi atau camilan..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-full bg-surface-container text-on-surface placeholder:text-outline text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all">
            </form>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-none">
            <a href="{{ route('catalog.index', ['kategori' => 'all', 'q' => request('q')]) }}" 
                class="px-5 py-2 rounded-full font-headline text-base font-bold transition-all shrink-0 {{ $selectedCat === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container text-secondary hover:bg-surface-container-high' }}">
                Semua Menu
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('catalog.index', ['kategori' => $cat->slug, 'q' => request('q')]) }}" 
                    class="px-5 py-2 rounded-full font-headline text-base font-bold transition-all shrink-0 {{ $selectedCat === $cat->slug ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container text-secondary hover:bg-surface-container-high' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Product Grid -->
        @if($products->isEmpty())
            <div class="text-center py-20 bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant/30">
                <span class="material-symbols-outlined text-6xl text-outline mb-2">coffee</span>
                <h3 class="font-headline text-2xl font-bold text-primary">Menu Tidak Ditemukan</h3>
                <p class="text-sm text-on-surface-variant mt-1">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-4 px-6 py-2 rounded-full bg-primary text-on-primary font-headline text-sm font-bold">
                    Reset Filter
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-surface-container-lowest rounded-3xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between border border-outline-variant/30 group">
                        <div>
                            <!-- Header Card -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-xs font-bold font-headline">
                                    {{ $product->category->name }}
                                </span>
                                @if($product->is_featured)
                                    <span class="flex items-center gap-1 text-[11px] font-bold text-secondary">
                                        <span class="material-symbols-outlined text-[14px]">star</span> Favorit
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-headline text-2xl font-bold text-primary group-hover:text-primary-container transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed line-clamp-2">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-6 pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-on-surface-variant block uppercase tracking-wider">Harga</span>
                                <span class="font-price text-2xl text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>

                            <button type="button" @click="openModal({{ json_encode($product) }})" 
                                class="px-5 py-2.5 rounded-full bg-primary-container text-on-primary font-headline text-sm font-bold hover:bg-primary transition-all flex items-center gap-1 active:scale-95 shadow-sm">
                                <span>Pilih</span>
                                <span class="material-symbols-outlined text-[16px]">add</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- MODAL DETAIL PRODUK (DETAIL_PRODUK_JELANGPULANG) -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div @click.away="modalOpen = false" class="bg-surface-container-lowest w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="p-5 border-b border-outline-variant/30 flex items-start justify-between bg-surface-container-low">
                    <div>
                        <span class="text-[10px] font-bold tracking-widest text-secondary uppercase block" x-text="selectedProduct?.category?.name"></span>
                        <h2 class="font-headline text-3xl font-bold text-primary leading-tight" x-text="selectedProduct?.name"></h2>
                        <p class="text-xs text-on-surface-variant mt-0.5 line-clamp-2" x-text="selectedProduct?.description"></p>
                    </div>
                    <button type="button" @click="modalOpen = false" class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface hover:bg-outline-variant transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form method="POST" action="{{ route('cart.add') }}" class="p-5 overflow-y-auto space-y-5 flex-1">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProduct?.id">

                    <!-- Option 1: Pilihan Varian Suhu (Hot / Ice) -->
                    <template x-if="selectedProduct && (selectedProduct.has_hot || selectedProduct.has_ice)">
                        <div class="space-y-2">
                            <label class="font-headline text-lg font-bold text-primary block">Pilihan Suhu</label>
                            <div class="grid grid-cols-2 gap-3">
                                <template x-if="selectedProduct.has_ice">
                                    <label class="cursor-pointer p-3 rounded-2xl border-2 flex items-center justify-between transition-all"
                                           :class="variant === 'ice' ? 'border-primary bg-primary/5 text-primary font-bold' : 'border-outline-variant/40 bg-surface text-on-surface'">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[20px] text-secondary">ac_unit</span>
                                            <div>
                                                <p class="text-sm">Dingin (Ice)</p>
                                                <p class="text-[11px] text-on-surface-variant" x-text="selectedProduct.ice_price_delta > 0 ? '+Rp ' + selectedProduct.ice_price_delta.toLocaleString('id-ID') : 'Harga Tetap'"></p>
                                            </div>
                                        </div>
                                        <input type="radio" name="variant" value="ice" x-model="variant" class="sr-only">
                                    </label>
                                </template>
                                <template x-if="selectedProduct.has_hot">
                                    <label class="cursor-pointer p-3 rounded-2xl border-2 flex items-center justify-between transition-all"
                                           :class="variant === 'hot' ? 'border-primary bg-primary/5 text-primary font-bold' : 'border-outline-variant/40 bg-surface text-on-surface'">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[20px] text-primary">local_cafe</span>
                                            <div>
                                                <p class="text-sm">Hangat (Hot)</p>
                                                <p class="text-[11px] text-on-surface-variant">Standar</p>
                                            </div>
                                        </div>
                                        <input type="radio" name="variant" value="hot" x-model="variant" class="sr-only">
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Option 2: Tingkat Manis (Hanya untuk Racikan) -->
                    <template x-if="selectedProduct && selectedProduct.type === 'racikan'">
                        <div class="space-y-2">
                            <label class="font-headline text-lg font-bold text-primary block">Tingkat Manis</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer p-2.5 rounded-xl border text-center transition-all"
                                       :class="sweetness === 'normal' ? 'border-primary bg-primary/10 text-primary font-bold' : 'border-outline-variant/40 text-on-surface-variant'">
                                    <input type="radio" name="sweetness" value="normal" x-model="sweetness" class="sr-only">
                                    <span class="text-xs block">Normal</span>
                                    <span class="text-[10px] text-outline block">100% Aren</span>
                                </label>
                                <label class="cursor-pointer p-2.5 rounded-xl border text-center transition-all"
                                       :class="sweetness === 'less' ? 'border-primary bg-primary/10 text-primary font-bold' : 'border-outline-variant/40 text-on-surface-variant'">
                                    <input type="radio" name="sweetness" value="less" x-model="sweetness" class="sr-only">
                                    <span class="text-xs block">Less Sweet</span>
                                    <span class="text-[10px] text-outline block">70%</span>
                                </label>
                                <label class="cursor-pointer p-2.5 rounded-xl border text-center transition-all"
                                       :class="sweetness === 'subtle' ? 'border-primary bg-primary/10 text-primary font-bold' : 'border-outline-variant/40 text-on-surface-variant'">
                                    <input type="radio" name="sweetness" value="subtle" x-model="sweetness" class="sr-only">
                                    <span class="text-xs block">Sedikit</span>
                                    <span class="text-[10px] text-outline block">30%</span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <!-- Option 3: Kepadatan Es (Hanya jika Varian Ice) -->
                    <template x-if="selectedProduct && selectedProduct.type === 'racikan' && variant === 'ice'">
                        <div class="space-y-2">
                            <label class="font-headline text-lg font-bold text-primary block">Kepadatan Es</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer p-2.5 rounded-xl border text-center transition-all"
                                       :class="iceLevel === 'normal_ice' ? 'border-primary bg-primary/10 text-primary font-bold' : 'border-outline-variant/40 text-on-surface-variant'">
                                    <input type="radio" name="ice_level" value="normal_ice" x-model="iceLevel" class="sr-only">
                                    <span class="text-xs block">Normal Ice</span>
                                </label>
                                <label class="cursor-pointer p-2.5 rounded-xl border text-center transition-all"
                                       :class="iceLevel === 'less_ice' ? 'border-primary bg-primary/10 text-primary font-bold' : 'border-outline-variant/40 text-on-surface-variant'">
                                    <input type="radio" name="ice_level" value="less_ice" x-model="iceLevel" class="sr-only">
                                    <span class="text-xs block">Less Ice</span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <!-- Option 4: Add-On Relevan (Pivot product_add_ons) -->
                    <template x-if="selectedProduct && selectedProduct.add_ons && selectedProduct.add_ons.length > 0">
                        <div class="space-y-2">
                            <label class="font-headline text-lg font-bold text-primary block">Pilihan Tambahan (Add-On)</label>
                            <div class="space-y-2">
                                <template x-for="addon in selectedProduct.add_ons" :key="addon.id">
                                    <label class="flex items-center justify-between p-3 rounded-2xl bg-surface-container-low border border-outline-variant/30 cursor-pointer hover:bg-surface-container transition-colors">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="add_ons[]" :value="addon.id" x-model="selectedAddons" 
                                                   class="w-4 h-4 rounded text-primary focus:ring-primary">
                                            <span class="text-sm font-semibold text-on-surface" x-text="addon.name"></span>
                                        </div>
                                        <span class="font-price text-sm text-secondary font-bold" x-text="'+ Rp ' + addon.price.toLocaleString('id-ID')"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Option 5: Catatan Khusus -->
                    <div class="space-y-1">
                        <label class="font-headline text-lg font-bold text-primary block">Catatan untuk Barista</label>
                        <input type="text" name="note" maxlength="100" placeholder="Contoh: Kopi diaduk ya, atau pisahkan sedotan..." 
                               class="w-full px-4 py-2.5 rounded-xl bg-surface-container text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>

                    <!-- Modal Action & Stepper -->
                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between gap-4">
                        <!-- Stepper Kuantitas -->
                        <div class="flex items-center bg-surface-container rounded-full p-1 border border-outline-variant/30">
                            <button type="button" @click="if (qty > 1) qty--" class="w-8 h-8 rounded-full bg-surface flex items-center justify-center font-bold text-primary shadow-sm hover:bg-surface-container-high">-</button>
                            <span class="w-10 text-center font-headline text-lg font-bold text-primary" x-text="qty"></span>
                            <button type="button" @click="qty++" class="w-8 h-8 rounded-full bg-surface flex items-center justify-center font-bold text-primary shadow-sm hover:bg-surface-container-high">+</button>
                            <input type="hidden" name="qty" :value="qty">
                        </div>

                        <!-- Tombol Submit Keranjang -->
                        <button type="submit" class="flex-1 py-3 px-5 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all flex items-center justify-between shadow-md active:scale-95">
                            <span>Tambah Pesanan</span>
                            <span class="font-price text-xl text-secondary-fixed" x-text="'Rp ' + calculateTotal().toLocaleString('id-ID')"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <!-- Alpine.js Component Script -->
    <script>
        function catalogApp() {
            return {
                modalOpen: false,
                selectedProduct: null,
                variant: 'ice',
                sweetness: 'normal',
                iceLevel: 'normal_ice',
                selectedAddons: [],
                qty: 1,

                openModal(product) {
                    this.selectedProduct = product;
                    this.variant = product.has_ice ? 'ice' : 'hot';
                    this.sweetness = 'normal';
                    this.iceLevel = 'normal_ice';
                    this.selectedAddons = [];
                    this.qty = 1;
                    this.modalOpen = true;
                },

                calculateTotal() {
                    if (!this.selectedProduct) return 0;
                    let base = this.selectedProduct.price;
                    if (this.variant === 'ice' && this.selectedProduct.ice_price_delta) {
                        base += this.selectedProduct.ice_price_delta;
                    }
                    let addonsTotal = 0;
                    if (this.selectedProduct.add_ons) {
                        this.selectedProduct.add_ons.forEach(a => {
                            if (this.selectedAddons.includes(a.id) || this.selectedAddons.includes(String(a.id))) {
                                addonsTotal += a.price;
                            }
                        });
                    }
                    return (base + addonsTotal) * this.qty;
                }
            };
        }
    </script>
</x-customer-layout>
