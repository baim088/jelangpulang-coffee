<x-customer-layout>
    <x-slot:title>Status Pesanan — {{ $order->order_no }}</x-slot:title>

    <div x-data="orderTracker('{{ $order->order_no }}', '{{ $order->status }}')" class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

        <!-- Header Card -->
        <div class="bg-surface-container rounded-3xl p-6 shadow-sm border border-outline-variant/30 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-wider block font-bold">NOMOR PESANAN</span>
                    <h1 class="font-headline text-3xl font-extrabold text-primary">{{ $order->order_no }}</h1>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Pemesan: <strong class="text-on-surface">{{ $order->customer_name }}</strong> 
                        @if($order->table_no) &bull; Lokasi: <strong class="text-primary">{{ $order->table_no }}</strong> @endif
                    </p>
                </div>

                <!-- Dynamic Status Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold font-headline shadow-sm"
                     :class="{
                        'bg-secondary-fixed text-on-secondary-container': currentStatus === 'menunggu_verifikasi',
                        'bg-primary-container text-on-primary': currentStatus === 'diproses',
                        'bg-emerald-600 text-white': currentStatus === 'selesai',
                        'bg-error text-white': currentStatus === 'dibatalkan'
                     }">
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    <span x-text="statusLabel"></span>
                </div>
            </div>
        </div>

        <!-- Rejection / Cancellation Alert (FR-B11) -->
        <template x-if="currentStatus === 'dibatalkan'">
            <div class="bg-error-container text-on-error-container rounded-3xl p-5 mb-6 shadow-sm border border-error/30 space-y-3">
                <div class="flex items-center gap-2 font-headline text-lg font-bold text-error">
                    <span class="material-symbols-outlined text-[22px]">cancel</span>
                    <span>Pesanan Dibatalkan</span>
                </div>
                <p class="text-xs leading-relaxed" x-text="cancelReason || 'Pesanan dibatalkan oleh kasir atau telah melewati batas waktu pembayaran.'"></p>
                
                @if($order->payment_method === 'qris')
                    <a href="{{ route('order.payment', $order->order_no) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-error text-white text-xs font-bold font-headline shadow-sm hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[16px]">upload_file</span>
                        <span>Unggah Ulang Bukti Bayar</span>
                    </a>
                @endif
            </div>
        </template>

        <!-- Prompt to upload proof if QRIS and no proof yet -->
        @if($order->payment_method === 'qris' && empty($order->payment_proof) && !$order->isDibatalkan())
            <div class="bg-secondary-fixed text-on-secondary-container rounded-3xl p-5 mb-6 shadow-sm border border-secondary/30 flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="font-headline text-lg font-bold">Bukti Transfer Belum Diunggah</h3>
                    <p class="text-xs">Selesaikan pembayaran QRIS agar pesanan Anda segera diproses kasir.</p>
                </div>
                <a href="{{ route('order.payment', $order->order_no) }}" class="px-5 py-2.5 rounded-full bg-primary text-on-primary text-xs font-bold font-headline shadow-sm hover:bg-primary-container shrink-0">
                    Buka QRIS & Unggah
                </a>
            </div>
        @endif

        <!-- Progress Steps (Visual Stepper) -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 mb-6">
            <h3 class="font-headline text-xl font-bold text-primary mb-6">Progres Pesanan Anda</h3>

            <div class="relative pl-6 border-l-2 border-outline-variant/40 space-y-8">
                <!-- Step 1: Menunggu Verifikasi -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                          :class="['menunggu_verifikasi', 'diproses', 'selesai'].includes(currentStatus) ? 'bg-primary text-white' : 'bg-outline-variant text-white'">
                        1
                    </span>
                    <h4 class="font-headline text-lg font-bold text-primary">Pesanan Diterima Sistem</h4>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        @if($order->payment_method === 'qris')
                            Menunggu verifikasi bukti transfer ShopeePay oleh kasir.
                        @else
                            Menunggu konfirmasi penerimaan tunai di meja kasir.
                        @endif
                    </p>
                </div>

                <!-- Step 2: Diproses / Diracik -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                          :class="['diproses', 'selesai'].includes(currentStatus) ? 'bg-primary text-white' : 'bg-surface-container-high text-outline'">
                        2
                    </span>
                    <h4 class="font-headline text-lg font-bold" :class="['diproses', 'selesai'].includes(currentStatus) ? 'text-primary' : 'text-outline'">
                        Sedang Diracik Barista
                    </h4>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Pembayaran sudah terverifikasi lunas. Barista sedang menyiapkan pesanan Anda dengan segar.
                    </p>
                </div>

                <!-- Step 3: Siap Diambil / Selesai -->
                <div class="relative">
                    <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                          :class="currentStatus === 'selesai' ? 'bg-emerald-600 text-white' : 'bg-surface-container-high text-outline'">
                        3
                    </span>
                    <h4 class="font-headline text-lg font-bold" :class="currentStatus === 'selesai' ? 'text-emerald-700' : 'text-outline'">
                        Pesanan Siap Diambil / Selesai
                    </h4>
                    <p class="text-xs text-on-surface-variant mt-0.5">
                        Minuman dan camilan sudah siap disajikan di meja Anda atau diambil di bar kasir.
                    </p>
                </div>
            </div>

            <!-- Polling Indicator -->
            <div class="mt-8 pt-4 border-t border-outline-variant/20 flex items-center justify-between text-[11px] text-on-surface-variant">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span>Pembaruan otomatis tiap 10 detik</span>
                </span>
                <button type="button" @click="fetchStatus()" class="hover:text-primary underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    <span>Segarkan Sekarang</span>
                </button>
            </div>
        </div>

        <!-- Ordered Items Summary -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <h3 class="font-headline text-xl font-bold text-primary pb-2 border-b border-outline-variant/20">
                Rincian Pesanan
            </h3>

            <div class="space-y-3">
                @foreach($order->items()->whereNull('parent_id')->with('addOns')->get() as $item)
                    <div class="flex items-start justify-between text-xs pb-2 border-b border-outline-variant/10">
                        <div>
                            <p class="font-bold text-on-surface text-sm">{{ $item->qty }}x {{ $item->product_name }} ({{ strtoupper($item->variant) }})</p>
                            @if($item->addOns->isNotEmpty())
                                <p class="text-secondary text-[11px] mt-0.5">
                                    Tambahan: {{ $item->addOns->pluck('product_name')->join(', ') }}
                                </p>
                            @endif
                            @if($item->note)
                                <p class="text-outline text-[11px] italic">"{{ $item->note }}"</p>
                            @endif
                        </div>
                        <span class="font-price text-sm font-bold text-on-surface">Rp {{ number_format($item->subtotal + $item->addOns->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-2 flex items-center justify-between font-headline text-xl font-bold text-primary">
                <span>Total Tagihan</span>
                <span class="font-price text-2xl text-secondary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    <!-- Polling Alpine Script (FR-B08) -->
    <script>
        function orderTracker(orderNo, initialStatus) {
            return {
                currentStatus: initialStatus,
                statusLabel: 'Menunggu Verifikasi',
                cancelReason: '{{ addslashes($order->cancel_reason ?? "") }}',

                init() {
                    this.updateLabel(this.currentStatus);

                    // Polling berkala 10 detik (FR-B08)
                    setInterval(() => {
                        this.fetchStatus();
                    }, 10000);
                },

                updateLabel(status) {
                    const map = {
                        'menunggu_verifikasi': 'Menunggu Verifikasi',
                        'diproses': 'Sedang Diracik',
                        'selesai': 'Siap Diambil / Selesai',
                        'dibatalkan': 'Dibatalkan'
                    };
                    this.statusLabel = map[status] || status;
                },

                fetchStatus() {
                    fetch(`/api/pesanan/${orderNo}/status`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.status) {
                                this.currentStatus = data.status;
                                this.statusLabel = data.status_label;
                                this.cancelReason = data.cancel_reason;
                            }
                        })
                        .catch(err => console.error('Gagal mengambil status pesanan:', err));
                }
            };
        }
    </script>
</x-customer-layout>
