<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Keuangan per Customer - Lancar Ekspedisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ea580c;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #ea580c;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-box {
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .summary-box.success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }
        .summary-box.warning {
            background-color: #fffbeb;
            border-color: #fef08a;
        }
        .summary-title {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f8f9fa;
            color: #444;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Lancar Ekspedisi</h1>
        <p>Rekapitulasi Keuangan per Customer</p>
        <p><strong>Periode:</strong> {{ $periode }}</p>
    </div>

    <!-- Summary Box -->
    <table class="summary-table">
        <tr>
            <td width="50%" style="padding-right: 10px;">
                <div class="summary-box success">
                    <div class="summary-title">TOTAL PEMASUKAN (LUNAS)</div>
                    <div class="summary-value">Rp {{ number_format($totalSemuaLunas, 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="50%" style="padding-left: 10px;">
                <div class="summary-box warning">
                    <div class="summary-title">TOTAL PIUTANG (BELUM BAYAR)</div>
                    <div class="summary-value">Rp {{ number_format($totalSemuaPending, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No.</th>
                <th>Nama Customer</th>
                <th class="text-center" width="15%">Total Pesanan</th>
                <th class="text-right" width="25%">Total Lunas (Rp)</th>
                <th class="text-right" width="25%">Total Piutang (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapCustomer as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->user->name ?? 'Customer Tidak Diketahui' }}</strong></td>
                <td class="text-center">{{ $item->total_pesanan }} Pesanan</td>
                <td class="text-right" style="color: #166534; font-weight: bold;">Rp {{ number_format($item->total_lunas, 0, ',', '.') }}</td>
                <td class="text-right" style="color: #92400e; font-weight: bold;">Rp {{ number_format($item->total_pending, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                    Tidak ada data rekapitulasi customer ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem Lancar Ekspedisi pada {{ now()->format('d M Y, H:i') }}</p>
    </div>

</body>
</html>
