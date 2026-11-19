<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $order->order_no }} — JelangPulang</title>
    <style>
        /* CSS Khusus Printer Thermal 58mm */
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 8px;
            width: 58mm;
            box-sizing: border-box;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .double-divider {
            border-top: 1px double #000;
            margin: 6px 0;
        }
        .flex {
            display: flex;
            justify-content: space-between;
        }

        /* Screen only styling */
        @media screen {
            body {
                margin: 20px auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.15);
                border-radius: 4px;
            }
            .no-print {
                margin-bottom: 12px;
                text-align: center;
            }
            .btn-print {
                padding: 6px 14px;
                background: #612b0f;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-family: sans-serif;
                font-size: 12px;
                font-weight: bold;
            }
        }

        @media print {
            .no-print {
                display: none;
            }
            body {
                width: 100%;
                margin: 0;
                padding: 4px;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Cetak Struk (Print)</button>
    </div>

    <!-- Header Toko -->
    <div class="text-center">
        <div class="font-bold" style="font-size: 14px; letter-spacing: 1px;">JELANGPULANG</div>
        <div style="font-size: 9px;">KEDAI KOPI SENJA & BERSANTAI</div>
        <div style="font-size: 9px; margin-top: 2px;">{{ $store->address ?? 'Jl. Menuju Senja No. 24' }}</div>
        <div style="font-size: 9px;">Telp: {{ $store->phone ?? '0812-3456-7890' }}</div>
    </div>

    <div class="divider"></div>

    <!-- Info Transaksi -->
    <div class="flex">
        <span>No: {{ $order->order_no }}</span>
        <span>{{ $order->created_at->format('d/m/y H:i') }}</span>
    </div>
    <div class="flex">
        <span>Kasir: {{ $order->processor->name ?? (auth()->user()->name ?? 'Kasir') }}</span>
        <span>Meja: {{ $order->table_no ?? 'Takeaway' }}</span>
    </div>
    <div>Pelanggan: {{ $order->customer_name }}</div>

    <div class="divider"></div>

    <!-- Daftar Item -->
    @foreach($order->items->whereNull('parent_id') as $item)
        <div>
            <div class="font-bold">{{ $item->product_name }}</div>
            <div class="flex" style="font-size: 10px; padding-left: 4px;">
                <span>
                    {{ $item->qty }} x {{ number_format($item->unit_price, 0, ',', '.') }}
                    @if($item->variant) ({{ ucfirst($item->variant) }}) @endif
                </span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>

            <!-- Add-ons -->
            @foreach($item->addOns as $addOn)
                <div class="flex" style="font-size: 9px; padding-left: 10px;">
                    <span>+ {{ $addOn->product_name }}</span>
                    <span>{{ number_format($addOn->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach

            @if($item->note)
                <div style="font-size: 9px; font-style: italic; padding-left: 6px;">*{{ $item->note }}</div>
            @endif
        </div>
    @endforeach

    <div class="divider"></div>

    <!-- Ringkasan Total & Pembayaran -->
    <div class="flex">
        <span>Subtotal:</span>
        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
    </div>
    <div class="flex font-bold" style="font-size: 12px; margin-top: 2px;">
        <span>TOTAL:</span>
        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
    </div>

    <div class="divider"></div>

    <div class="flex">
        <span>Metode:</span>
        <span class="font-bold uppercase">{{ $order->payment_method === 'cash' ? 'TUNAI' : 'QRIS SHOPEEPAY' }}</span>
    </div>

    @if($order->payment_method === 'cash' && $order->cash_received)
        <div class="flex">
            <span>Diterima:</span>
            <span>Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span>
        </div>
        <div class="flex font-bold">
            <span>Kembalian:</span>
            <span>Rp {{ number_format($order->change_amount ?? 0, 0, ',', '.') }}</span>
        </div>
    @elseif($order->payment_method === 'qris')
        <div class="flex">
            <span>Status:</span>
            <span class="font-bold">LUNAS</span>
        </div>
    @endif

    <div class="double-divider"></div>

    <!-- Footer -->
    <div class="text-center" style="font-size: 9px; margin-top: 6px;">
        <div>Terima kasih atas kunjungan Anda.</div>
        <div style="margin-top: 2px;">Selamat Menikmati Jelang Pulang.</div>
        <div style="margin-top: 4px; font-size: 8px;">Wi-Fi: JelangPulang_Guest | Pass: senjadikopi</div>
    </div>

</body>
</html>
