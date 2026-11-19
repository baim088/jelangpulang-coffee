<x-kasir-layout>
    <x-slot name="title">Rekap Shift Kasir — JelangPulang</x-slot>

    <div class="flex flex-col gap-6">

        <!-- HEADER & AKSI REKAP -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-bold uppercase tracking-wider">
                        Sesi Kasir Aktif
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-surface-container-high text-primary text-[11px] font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        Printer 58mm Siap
                    </span>
                </div>
                <h1 class="font-headline text-3xl font-bold text-primary uppercase tracking-tight">Rekap Shift Kasir</h1>
                <p class="text-xs text-on-surface-variant max-w-2xl mt-0.5">
                    Laporan transaksi shift berjalan untuk rekonsiliasi laci kas tunai dan verifikasi transaksi QRIS toko.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-primary text-on-primary hover:bg-primary-container transition-all flex items-center gap-2 text-xs font-bold shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">print</span>
                    <span>Cetak Laporan Rekap</span>
                </button>
            </div>
        </div>

        <!-- INFO STRIP METADATA SHIFT -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 rounded-2xl bg-surface-container-low border border-surface-container-highest/60 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider">Kasir Aktif</p>
                    <p class="text-xs font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider">Tanggal</p>
                    <p class="text-xs font-bold text-on-surface truncate">{{ now()->isoFormat('D MMMM Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider">Jam Buka Sesi</p>
                    <p class="text-xs font-bold text-on-surface truncate">15:00 WIB - Sekarang</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">point_of_sale</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-on-surface-variant uppercase font-bold tracking-wider">Status Laci Kas</p>
                    <p class="text-xs font-bold text-emerald-700 truncate">Seimbang / Terverifikasi</p>
                </div>
            </div>
        </div>

        <!-- 4 BENTO STATISTIC CARDS (FR-C09) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Omzet -->
            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container-highest/60 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Omzet Hari Ini</span>
                    <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
                </div>
                <div class="mt-3">
                    <span class="font-headline text-3xl font-bold text-primary block">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $totalTransaksi }} pesanan terselesaikan</p>
                </div>
            </div>

            <!-- Card 2: Kas Tunai -->
            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container-highest/60 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Kas Tunai (Cash)</span>
                    <span class="material-symbols-outlined text-secondary text-[20px]">point_of_sale</span>
                </div>
                <div class="mt-3">
                    <span class="font-headline text-3xl font-bold text-secondary block">Rp {{ number_format($totalCash, 0, ',', '.') }}</span>
                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $orders->where('payment_method', 'cash')->count() }} transaksi uang fisik</p>
                </div>
            </div>

            <!-- Card 3: QRIS -->
            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container-highest/60 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total QRIS ShopeePay</span>
                    <span class="material-symbols-outlined text-cyan-700 text-[20px]">qr_code_scanner</span>
                </div>
                <div class="mt-3">
                    <span class="font-headline text-3xl font-bold text-cyan-800 block">Rp {{ number_format($totalQris, 0, ',', '.') }}</span>
                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $orders->where('payment_method', 'qris')->count() }} transaksi digital</p>
                </div>
            </div>

            <!-- Card 4: Dibatalkan / Void -->
            <div class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container-highest/60 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Pesanan Dibatalkan</span>
                    <span class="material-symbols-outlined text-error text-[20px]">cancel</span>
                </div>
                <div class="mt-3">
                    <span class="font-headline text-3xl font-bold text-error block">{{ $cancelledOrders }}</span>
                    <p class="text-[11px] text-on-surface-variant mt-1">Stok telah dikembalikan</p>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN TRANSAKSI SHIFT -->
        <div class="bg-surface-container-lowest rounded-2xl border border-surface-container-highest/60 shadow-sm p-6 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-headline text-lg font-bold text-primary">Daftar Transaksi Lunas Shift Ini</h3>
                    <p class="text-xs text-on-surface-variant">Rincian seluruh pesanan yang telah diproses dan terverifikasi lunas.</p>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="py-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-[40px] text-surface-container-highest mb-1">receipt_long</span>
                    <p class="text-xs">Belum ada transaksi lunas pada shift hari ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-surface-container-highest bg-surface-container-low text-on-surface-variant uppercase font-bold text-[10px]">
                            <tr>
                                <th class="p-3">Waktu</th>
                                <th class="p-3">No. Pesanan</th>
                                <th class="p-3">Nama Pemesan</th>
                                <th class="p-3">Kanal / Meja</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3 text-right">Nominal</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-highest/40">
                            @foreach($orders as $ord)
                                <tr class="hover:bg-surface-container-low/50">
                                    <td class="p-3 text-on-surface-variant whitespace-nowrap">{{ $ord->created_at->format('H:i') }} WIB</td>
                                    <td class="p-3 font-bold text-primary whitespace-nowrap">{{ $ord->order_no }}</td>
                                    <td class="p-3 font-semibold text-on-surface">{{ $ord->customer_name }}</td>
                                    <td class="p-3 text-on-surface-variant">
                                        {{ $ord->table_no ? 'Meja ' . $ord->table_no : 'Takeaway' }}
                                        <span class="text-[10px] text-outline block">({{ $ord->channel === 'self_order' ? 'Self-Order' : 'POS Walk-in' }})</span>
                                    </td>
                                    <td class="p-3 uppercase font-semibold text-secondary whitespace-nowrap">
                                        {{ $ord->payment_method === 'cash' ? 'Tunai' : 'QRIS' }}
                                    </td>
                                    <td class="p-3 text-right font-bold text-on-surface whitespace-nowrap">
                                        Rp {{ number_format($ord->total, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ord->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($ord->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('kasir.struk', $ord->order_no) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-surface-container-high hover:bg-primary hover:text-on-primary text-xs font-semibold inline-flex items-center gap-1 transition-all">
                                            <span class="material-symbols-outlined text-[14px]">print</span>
                                            <span>Struk</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-kasir-layout>
