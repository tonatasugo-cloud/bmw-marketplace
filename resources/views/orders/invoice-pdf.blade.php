<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .container { padding: 30px; }
        .header { border-bottom: 3px solid #1e40af; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e40af; }
        .logo span { color: #1e3a8a; }
        .invoice-number { float: right; text-align: right; }
        .invoice-number h2 { margin: 0; color: #1e40af; }
        .info-grid { display: table; width: 100%; margin-bottom: 30px; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; }
        .info-box h3 { font-size: 14px; color: #1e40af; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.items th { background: #1e40af; color: white; padding: 10px; text-align: left; }
        table.items td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-table { width: 50%; float: right; margin-top: 20px; }
        .total-table td { padding: 8px; }
        .total-table .label { color: #666; }
        .total-table .amount { text-align: right; font-weight: bold; }
        .grand-total { background: #1e40af; color: white; }
        .grand-total td { padding: 12px; font-size: 14px; }
        .status { padding: 10px 20px; background: #dcfce7; color: #166534; text-align: center; margin: 20px 0; border-radius: 5px; }
        .footer { margin-top: 50px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <div class="logo">
            NICOLAS<span>SELECT</span>
        </div>
        <div class="invoice-number">
            <h2>INVOICE</h2>
            <p style="margin: 5px 0 0 0;">
                <strong>No:</strong> {{ $order->order_number }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>

    {{-- Info --}}
    <div class="info-grid">
        <div class="info-box">
            <h3>PEMBELI</h3>
            <p>
                <strong>{{ $order->user->name ?? '-' }}</strong><br>
                {{ $order->user->email ?? '-' }}<br>
                {{ $order->user->phone ?? '-' }}
            </p>
        </div>
        <div class="info-box">
            <h3>PENJUAL</h3>
            <p>
                <strong>{{ $order->seller->store_name ?? '-' }}</strong><br>
                {{ $order->seller->address ?? '-' }}<br>
                {{ $order->seller->city ?? '' }}, {{ $order->seller->province ?? '' }}<br>
                {{ $order->seller->phone ?? '-' }}
            </p>
        </div>
    </div>

    {{-- Status --}}
    <div class="status">
        STATUS: {{ strtoupper($order->status_label) }}
    </div>

    {{-- Item --}}
    <table class="items">
        <thead>
            <tr>
                <th>Kendaraan</th>
                <th>Tahun</th>
                <th style="text-align:right;">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $order->car->title ?? 'Mobil' }}</strong><br>
                    <small>{{ $order->car->series->name ?? '' }} · {{ $order->car->transmission ?? '' }} · {{ $order->car->fuel_type ?? '' }}</small>
                </td>
                <td>{{ $order->car->year ?? '-' }}</td>
                <td style="text-align:right;">Rp {{ number_format($order->car_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    <table class="total-table">
        <tr>
            <td class="label">Harga Mobil</td>
            <td class="amount">Rp {{ number_format($order->car_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Booking Fee</td>
            <td class="amount">Rp {{ number_format($order->booking_fee, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Sudah Dibayar</td>
            <td class="amount">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td>SISA PEMBAYARAN</td>
            <td class="amount">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div style="clear: both;"></div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            Invoice ini dibuat otomatis oleh sistem Nicolas Select.<br>
            Untuk pertanyaan, hubungi support@nicolasaselect.id
        </p>
        <p>&copy; {{ date('Y') }} Nicolas Select. All rights reserved.</p>
    </div>
</div>
</body>
</html>