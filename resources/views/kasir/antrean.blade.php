<x-kasir-layout>
    <x-slot name="title">Antrean Pesanan Masuk — JelangPulang</x-slot>

    <div x-data="{ 
        previewModal: false, 
        previewImg: '', 
        previewOrderNo: '', 
        previewTotal: '',
        cancelModal: false,
        cancelActionUrl: '',
        cancelOrderNo: '',
        showCancelModal(url, no) {
            this.cancelActionUrl = url;
            this.cancelOrderNo = no;
            this.cancelModal = true;
        },
        showProofModal(url, no, total) {
            this.previewImg = url;
            this.previewOrderNo = no;
            this.previewTotal = total;
            this.previewModal = true;
        }
    }">

        <!-- TOP HEADER & METRICS RIBBON -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 text-secondary text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    <span>Operasional Meja & POS</span>
                </div>
                <h1 class="font-headline text-3xl font-bold text-primary tracking-tight mt-1">Antrean Pesanan Masuk</h1>
                <p class="text-sm text-on-surface-variant">Pantau pesanan self-order pelanggan dan verifikasi bukti pembayaran QRIS secara real-time.</p>
            </div>

            <!-- Quick Metrics Ribbon (FR-C01) -->
            <div class="flex flex-wrap items-center gap-2 bg-surface-container-low p-2 rounded-2xl border border-surface-container-highest/60 shadow-sm">
                <a href="{{ route('kasir.antrean', ['status' => 'menunggu_verifikasi']) }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $status === 'menunggu_verifikasi' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-high/60 text-on-surface hover:bg-secondary-container/40' }} transition-colors">
                    <span class="material-symbols-outlined text-[18px]">hourglass_top</span>
                    <div class="flex flex-col">
                        <span class="text-xs">Menunggu</span>
                        <span class="font-headline text-lg font-bold leading-none">{{ $metrics['menunggu'] }} Pesanan</span>
                    </div>
                </a>

                <a href="{{ route('kasir.antrean', ['status' => 'diproses']) }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $status === 'diproses' ? 'bg-primary text-on-primary' : 'bg-surface-container-high/60 text-on-surface hover:bg-primary/10' }} transition-colors">
                    <span class="material-symbols-outlined text-[18px]">blender</span>
                    <div class="flex flex-col">
                        <span class="text-xs">Diproses</span>
                        <span class="font-headline text-lg font-bold leading-none">{{ $metrics['diproses'] }} Aktif</span>
                    </div>
                </a>

                <a href="{{ route('kasir.antrean', ['status' => 'selesai']) }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $status === 'selesai' ? 'bg-emerald-600 text-white' : 'bg-surface-container-high/60 text-on-surface hover:bg-emerald-50' }} transition-colors">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <div class="flex flex-col">
                        <span class="text-xs">Selesai Hari Ini</span>
                        <span class="font-headline text-lg font-bold leading-none">{{ $metrics['selesai'] }}</span>
                    </div>
                </a>

                <a href="{{ route('kasir.antrean', ['status' => 'dibatalkan']) }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-xl {{ $status === 'dibatalkan' ? 'bg-error text-white' : 'bg-surface-container-high/60 text-on-surface hover:bg-error/10' }} transition-colors">
                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                    <div class="flex flex-col">
                        <span class="text-xs">Dibatalkan</span>
                        <span class="font-headline text-lg font-bold leading-none">{{ $metrics['dibatalkan'] }}</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- FILTER TAB & SEARCH CONTROLS (FR-C01) -->
        <div class="bg-surface-container-lowest rounded-2xl p-4 border border-surface-container-highest/60 shadow-sm flex flex-col gap-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Status Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('kasir.antrean', ['status' => 'all', 'search' => $search, 'dining' => $dining]) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ $status === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface' }}">
                        Semua
                    </a>
                    <a href="{{ route('kasir.antrean', ['status' => 'menunggu_verifikasi', 'search' => $search, 'dining' => $dining]) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold transition-all flex items-center gap-1.5 {{ $status === 'menunggu_verifikasi' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface' }}">
                        @if($metrics['menunggu'] > 0)
                            <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                        @endif
                        Menunggu Verifikasi ({{ $metrics['menunggu'] }})
                    </a>
                    <a href="{{ route('kasir.antrean', ['status' => 'diproses', 'search' => $search, 'dining' => $dining]) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ $status === 'diproses' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface' }}">
                        Diproses ({{ $metrics['diproses'] }})
                    </a>
                    <a href="{{ route('kasir.antrean', ['status' => 'selesai', 'search' => $search, 'dining' => $dining]) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ $status === 'selesai' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface' }}">
                        Selesai
                    </a>
                    <a href="{{ route('kasir.antrean', ['status' => 'dibatalkan', 'search' => $search, 'dining' => $dining]) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ $status === 'dibatalkan' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface' }}">
                        Dibatalkan
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="window.location.reload()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-high hover:bg-secondary-container hover:text-on-secondary-container transition-all text-xs font-semibold text-on-surface">
                        <span class="material-symbols-outlined text-[16px]">sync</span>
                        <span>Segarkan</span>
                    </button>
                </div>
            </div>

            <!-- Search & Filter Options -->
            <form action="{{ route('kasir.antrean') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2 border-t border-surface-container-highest/40">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="md:col-span-7 relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px]">search</span>
                    <input type="text" name="search" value="{{ $search }}" 
                           placeholder="Cari No. Pesanan (misal JP-2026...) atau Nama Pemesan..." 
                           class="w-full pl-10 pr-4 py-2 rounded-full bg-surface-container-low text-xs text-on-surface placeholder:text-outline focus:outline-none focus:ring-1 focus:ring-primary border border-surface-container-highest/60">
                </div>

                <div class="md:col-span-3">
                    <select name="dining" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-full bg-surface-container-low text-xs text-on-surface focus:outline-none border border-surface-container-highest/60">
                        <option value="all" {{ $dining === 'all' ? 'selected' : '' }}>Semua Lokasi (Dine-in & Takeaway)</option>
                        <option value="dine_in" {{ $dining === 'dine_in' ? 'selected' : '' }}>Hanya Dine-in (Meja)</option>
                        <option value="takeaway" {{ $dining === 'takeaway' ? 'selected' : '' }}>Hanya Takeaway</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2 rounded-full bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container transition-all">
                        Cari
                    </button>
                    @if($search || $dining !== 'all')
                        <a href="{{ route('kasir.antrean', ['status' => $status]) }}" class="px-3 py-2 rounded-full bg-surface-container text-xs text-on-surface-variant hover:text-on-surface" title="Reset filter">
                            &times;
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ORDER CARDS GRID -->
        @if($orders->isEmpty())
            <div class="bg-surface-container-lowest rounded-2xl p-12 text-center border border-surface-container-highest/60 shadow-sm flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-[64px] text-surface-container-highest mb-2">inbox</span>
                <h3 class="font-headline text-xl font-bold text-on-surface">Tidak ada pesanan ditemukan</h3>
                <p class="text-xs text-on-surface-variant mt-1 max-w-sm">Belum ada pesanan yang sesuai dengan filter antrean yang dipilih saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($orders as $order)
                    <div class="bg-surface-container-lowest rounded-2xl p-5 border {{ $order->isMenungguVerifikasi() ? 'border-secondary/40 shadow-md ring-1 ring-secondary/20' : 'border-surface-container-highest/60 shadow-sm' }} flex flex-col justify-between transition-all">
                        
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-surface-container-highest/40">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-headline text-lg font-bold text-primary">{{ $order->order_no }}</span>
                                    @if($order->table_no)
                                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-container/60 text-on-secondary-container text-xs font-bold">
                                            Meja {{ $order->table_no }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-xs font-bold">
                                            Takeaway
                                        </span>
                                    @endif

                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $order->channel === 'self_order' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-high text-on-surface-variant' }}">
                                        {{ $order->channel === 'self_order' ? 'Self-Order' : 'Kasir Walk-in' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-1 text-xs text-on-surface-variant">
                                    <span class="font-semibold text-on-surface">{{ $order->customer_name }}</span>
                                    <span>•</span>
                                    <span>{{ $order->created_at->isoFormat('HH:mm') }} WIB ({{ $order->created_at->diffForHumans() }})</span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if($order->isMenungguVerifikasi())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-ping"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($order->isDiproses())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold border border-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        Diproses Barista
                                    </span>
                                @elseif($order->isSelesai())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold border border-rose-300">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Item Pesanan List -->
                        <div class="py-3 flex-1 flex flex-col gap-2">
                            @foreach($order->items->whereNull('parent_id') as $item)
                                <div class="flex items-start justify-between text-xs">
                                    <div class="flex-1 pr-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-primary">{{ $item->qty }}x</span>
                                            <span class="font-semibold text-on-surface">{{ $item->product_name }}</span>
                                            @if($item->variant)
                                                <span class="text-[11px] px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant font-medium">
                                                    {{ ucfirst($item->variant) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Opsi / Addon -->
                                        @if(!empty($item->options) || $item->addOns->isNotEmpty())
                                            <div class="pl-5 mt-0.5 text-[11px] text-on-surface-variant space-y-0.5">
                                                @if(isset($item->options['sweetness']))
                                                    <div>Gula: {{ $item->options['sweetness'] }}</div>
                                                @endif
                                                @if(isset($item->options['ice_level']))
                                                    <div>Es: {{ $item->options['ice_level'] }}</div>
                                                @endif
                                                @foreach($item->addOns as $addOn)
                                                    <div class="text-secondary font-medium">+ {{ $addOn->product_name }}</div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($item->note)
                                            <div class="pl-5 mt-1 text-[11px] text-amber-800 italic flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[13px]">chat_bubble_outline</span>
                                                <span>"{{ $item->note }}"</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="font-semibold text-on-surface whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal + $item->addOns->sum('subtotal'), 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            @if($order->cancel_reason)
                                <div class="mt-2 p-2 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-800">
                                    <strong>Alasan Batal:</strong> {{ $order->cancel_reason }}
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer (Total, Bukti Transfer, Aksi Kasir) -->
                        <div class="pt-3 border-t border-surface-container-highest/40 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-on-surface-variant">Metode:</span>
                                    <span class="px-2 py-0.5 rounded bg-surface-container text-xs font-semibold text-on-surface uppercase">
                                        {{ $order->payment_method === 'qris' ? 'QRIS ShopeePay' : ($order->payment_method === 'cash' ? 'Tunai / Kasir' : 'Bayar di Kasir') }}
                                    </span>

                                    <!-- Tombol Pratinjau Bukti Bayar QRIS (FR-C02) -->
                                    @if($order->payment_proof)
                                        <button type="button" 
                                                @click="showProofModal('{{ asset('storage/' . $order->payment_proof) }}', '{{ $order->order_no }}', 'Rp {{ number_format($order->total, 0, ',', '.') }}')"
                                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-container/80 text-on-secondary-container text-xs font-semibold hover:bg-secondary-container transition-all">
                                            <span class="material-symbols-outlined text-[14px]">image</span>
                                            <span>Lihat Bukti Transfer</span>
                                        </button>
                                    @elseif($order->payment_method === 'qris' && $order->isMenungguVerifikasi())
                                        <span class="text-[11px] text-error font-medium italic">Belum unggah bukti</span>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <span class="text-[11px] text-on-surface-variant block">Total Pembayaran</span>
                                    <span class="font-headline text-xl font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi Kasir Berdasarkan Status -->
                            <div class="flex items-center justify-end gap-2 pt-1">
                                @if($order->isMenungguVerifikasi())
                                    <button type="button" 
                                            @click="showCancelModal('{{ route('kasir.antrean.batalkan', $order->order_no) }}', '{{ $order->order_no }}')"
                                            class="px-4 py-2 rounded-xl bg-surface-container text-error hover:bg-error-container hover:text-on-error-container transition-all text-xs font-semibold">
                                        Tolak / Batalkan
                                    </button>

                                    <form action="{{ route('kasir.antrean.verifikasi', $order->order_no) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran dan proses pesanan ini?')">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1.5 px-5 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container transition-all text-xs font-bold shadow-sm">
                                            <span class="material-symbols-outlined text-[16px]">check</span>
                                            <span>Terima & Proses (Potong Stok)</span>
                                        </button>
                                    </form>
                                @elseif($order->isDiproses())
                                    <button type="button" 
                                            @click="showCancelModal('{{ route('kasir.antrean.batalkan', $order->order_no) }}', '{{ $order->order_no }}')"
                                            class="px-3 py-2 rounded-xl bg-surface-container text-on-surface-variant hover:text-error transition-all text-xs font-medium">
                                        Batalkan Pesanan
                                    </button>

                                    <a href="{{ route('kasir.struk', $order->order_no) }}" target="_blank" class="flex items-center gap-1 px-3 py-2 rounded-xl bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-all text-xs font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">print</span>
                                        <span>Cetak Struk</span>
                                    </a>

                                    <form action="{{ route('kasir.antrean.selesai', $order->order_no) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition-all text-xs font-bold shadow-sm">
                                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                                            <span>Tandai Selesai</span>
                                        </button>
                                    </form>
                                @elseif($order->isSelesai())
                                    <a href="{{ route('kasir.struk', $order->order_no) }}" target="_blank" class="flex items-center gap-1 px-4 py-2 rounded-xl bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-all text-xs font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">print</span>
                                        <span>Cetak Ulang Struk</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- PAGINASI -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif

        <!-- MODAL PRATINJAU BUKTI BAYAR QRIS (FR-C02) -->
        <div x-cloak x-show="previewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-surface-container-highest/60 flex flex-col gap-4" @click.away="previewModal = false">
                <div class="flex items-center justify-between border-b border-surface-container-highest/40 pb-3">
                    <div>
                        <h3 class="font-headline text-lg font-bold text-primary">Bukti Transfer QRIS ShopeePay</h3>
                        <p class="text-xs text-on-surface-variant">Pesanan <span class="font-bold text-on-surface" x-text="previewOrderNo"></span> • Tagihan <span class="font-bold text-secondary" x-text="previewTotal"></span></p>
                    </div>
                    <button type="button" @click="previewModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
                </div>

                <div class="rounded-xl overflow-hidden bg-black/5 max-h-[60vh] flex items-center justify-center">
                    <img :src="previewImg" alt="Bukti Transfer" class="max-h-[60vh] w-auto object-contain">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="previewModal = false" class="px-5 py-2 rounded-xl bg-surface-container-high text-on-surface font-semibold text-xs hover:bg-surface-container-highest">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL PEMBATALAN PESANAN (FR-C02) -->
        <div x-cloak x-show="cancelModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-surface rounded-2xl max-w-md w-full p-6 shadow-2xl border border-surface-container-highest/60 flex flex-col gap-4" @click.away="cancelModal = false">
                <div class="flex items-center justify-between border-b border-surface-container-highest/40 pb-3">
                    <div class="flex items-center gap-2 text-error">
                        <span class="material-symbols-outlined text-[24px]">warning</span>
                        <h3 class="font-headline text-lg font-bold">Batalkan Pesanan</h3>
                    </div>
                    <button type="button" @click="cancelModal = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold">&times;</button>
                </div>

                <form :action="cancelActionUrl" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <p class="text-xs text-on-surface-variant">
                        Apakah Anda yakin ingin membatalkan pesanan <span class="font-bold text-on-surface" x-text="cancelOrderNo"></span>? Jika pesanan sudah sempat diproses, stok bahan yang dipotong akan dikembalikan otomatis.
                    </p>

                    <div>
                        <label class="block text-xs font-semibold text-on-surface mb-1">Alasan Pembatalan (Wajib)</label>
                        <textarea name="cancel_reason" required rows="3" 
                                  placeholder="Contoh: Bukti transfer tidak valid, stok bahan habis mendadak, atau pelanggan membatalkan..."
                                  class="w-full p-3 rounded-xl bg-surface-container-low text-xs border border-surface-container-highest focus:ring-1 focus:ring-error focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-surface-container-highest/40">
                        <button type="button" @click="cancelModal = false" class="px-4 py-2 rounded-xl bg-surface-container text-on-surface text-xs font-semibold hover:bg-surface-container-high">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-error text-white text-xs font-bold hover:bg-error/90 transition-all">
                            Konfirmasi Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-kasir-layout>
