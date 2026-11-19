<x-customer-layout>
    <x-slot:title>Riwayat Pesanan — JelangPulang</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8 pb-4 border-b border-outline-variant/30 flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-primary tracking-tight uppercase leading-none">
                    Riwayat Pesanan Saya
                </h1>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">Daftar transaksi kopi dan cerita Anda di JelangPulang.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full bg-primary-container text-on-primary font-headline text-sm font-bold shadow-sm hover:bg-primary transition-all">
                <span class="material-symbols-outlined text-[16px]">add</span>
                <span>Pesan Lagi</span>
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-20 bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant/30">
                <span class="material-symbols-outlined text-6xl text-outline mb-2">history</span>
                <h3 class="font-headline text-2xl font-bold text-primary">Belum Ada Riwayat Pesanan</h3>
                <p class="text-sm text-on-surface-variant mt-1">Anda belum pernah melakukan pemesanan.</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-full bg-primary text-on-primary font-headline text-base font-bold shadow-md hover:bg-primary-container transition-all">
                    <span>Mulai Pesan Sekarang</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-surface-container-lowest rounded-3xl p-5 shadow-sm border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-headline text-xl font-bold text-primary">{{ $order->order_no }}</span>
                                <span class="text-xs text-on-surface-variant">&bull; {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-xs text-on-surface-variant">
                                {{ $order->items->pluck('product_name')->take(3)->join(', ') }}
                                @if($order->items->count() > 3) dkk. @endif
                            </p>
                            <p class="text-xs font-semibold text-secondary">
                                Metode: {{ strtoupper($order->payment_method) }} &bull; Total: Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Badge Status -->
                            <span class="px-3 py-1 rounded-full text-xs font-headline font-bold
                                @if($order->status === 'menunggu_verifikasi') bg-secondary-fixed text-on-secondary-container
                                @elseif($order->status === 'diproses') bg-primary-container text-on-primary
                                @elseif($order->status === 'selesai') bg-emerald-100 text-emerald-800
                                @else bg-error-container text-on-error-container @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>

                            <a href="{{ route('order.status', $order->order_no) }}" class="px-4 py-2 rounded-full bg-surface-container text-primary font-headline text-xs font-bold hover:bg-surface-container-high transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif

    </div>
</x-customer-layout>
