<x-customer-layout>
    <x-slot:title>Pembayaran QRIS — {{ $order->order_no }}</x-slot:title>

    <div x-data="paymentTimer('{{ $order->expired_at?->toIso8601String() }}')" class="max-w-xl mx-auto px-4 sm:px-6 py-8">

        <!-- Top Order Info Card -->
        <div class="bg-surface-container rounded-3xl p-5 shadow-sm flex items-center justify-between mb-5 border border-outline-variant/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                </div>
                <div>
                    <span class="text-[10px] text-on-surface-variant uppercase tracking-wider block">Nomor Pesanan</span>
                    <span class="font-headline text-xl font-bold text-primary">{{ $order->order_no }}</span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-container text-xs font-bold font-headline">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                Menunggu Bukti
            </span>
        </div>

        <!-- Countdown Timer -->
        <div class="bg-surface-container-low rounded-3xl p-5 shadow-sm border border-outline-variant/30 mb-5">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2 text-secondary">
                    <span class="material-symbols-outlined text-[20px]">timer</span>
                    <span class="text-xs font-bold uppercase tracking-wider">Batas Waktu Pembayaran</span>
                </div>
                <div class="font-headline text-xl font-bold text-primary" x-text="timeLeftFormatted">
                    30:00
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant leading-relaxed">
                Segera selesaikan pembayaran dan unggah bukti transfer sebelum pesanan kedaluwarsa secara otomatis.
            </p>
        </div>

        <!-- Total Bill Card -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 text-center mb-6">
            <span class="text-xs text-on-surface-variant uppercase tracking-wider block mb-1">Total Tagihan Tepat</span>
            <div class="font-price text-4xl sm:text-5xl font-extrabold text-primary">
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </div>
            <p class="text-xs text-secondary font-medium mt-1">Pastikan nominal transfer sesuai persis hingga digit terakhir.</p>
        </div>

        <!-- QRIS Display Card -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 text-center mb-6">
            <div class="mb-4">
                <span class="text-xs font-bold tracking-widest text-secondary uppercase">QRIS SHOPEEPAY TOKO</span>
                <h3 class="font-headline text-2xl font-bold text-primary">NMID: ID10200234891</h3>
            </div>

            <!-- QR Image Box -->
            <div class="p-4 bg-white rounded-2xl shadow-inner border border-outline-variant/30 inline-block mx-auto mb-4">
                <img src="{{ $qrisImage }}" alt="QRIS ShopeePay Toko JelangPulang" class="w-56 h-56 sm:w-64 sm:h-64 object-contain mx-auto">
            </div>

            <p class="text-xs text-on-surface-variant max-w-sm mx-auto leading-relaxed">
                Scan kode QRIS di atas memakai aplikasi BCA, ShopeePay, GoPay, OVO, Dana, atau mobile banking lainnya.
            </p>
        </div>

        <!-- Upload Proof Form (FR-B07, BR-16) -->
        <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant/30 mb-8">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-outline-variant/20">
                <span class="material-symbols-outlined text-primary text-[22px]">upload_file</span>
                <h3 class="font-headline text-xl font-bold text-primary">Unggah Bukti Transfer</h3>
            </div>

            <form method="POST" action="{{ route('order.upload_proof', $order->order_no) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                        Pilih Tangkapan Layar / Foto Bukti (Maks. 2 MB)
                    </label>
                    <input type="file" name="payment_proof" accept="image/png, image/jpeg, image/jpg" required
                        class="w-full text-xs text-on-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-container file:text-on-primary hover:file:bg-primary file:cursor-pointer p-2 rounded-2xl bg-surface-container border border-outline-variant/30">
                    @error('payment_proof')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-full bg-primary text-on-primary font-headline text-lg font-bold hover:bg-primary-container transition-all shadow-md flex items-center justify-center gap-2 active:scale-95 text-center">
                    <span>Kirim Bukti Pembayaran</span>
                    <span class="material-symbols-outlined text-[18px]">send</span>
                </button>
            </form>
        </div>

        <!-- Back to Status link -->
        <div class="text-center">
            <a href="{{ route('order.status', $order->order_no) }}" class="text-xs text-secondary font-semibold hover:underline">
                Sudah pernah unggah? Cek status pesanan di sini &rarr;
            </a>
        </div>

    </div>

    <!-- Timer Script -->
    <script>
        function paymentTimer(expiredAtString) {
            return {
                timeLeftFormatted: '30:00',
                init() {
                    if (!expiredAtString) return;
                    const expiredTime = new Date(expiredAtString).getTime();

                    const update = () => {
                        const now = new Date().getTime();
                        const distance = expiredTime - now;

                        if (distance <= 0) {
                            this.timeLeftFormatted = 'Kedaluwarsa';
                            return;
                        }

                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        this.timeLeftFormatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    };

                    update();
                    setInterval(update, 1000);
                }
            };
        }
    </script>
</x-customer-layout>
