<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - Lancar Ekspedisi</title>
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
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
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
        <p>Laporan Keuangan & Transaksi</p>
        <p><strong>Periode:</strong> {{ $periode }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td style="padding-right: 10px; width: 50%;">
                <div class="summary-box success">
                    <div class="summary-title">Total Pemasukan (Lunas)</div>
                    <div class="summary-value">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="padding-left: 10px; width: 50%;">
                <div class="summary-box warning">
                    <div class="summary-title">Total Tagihan (Belum Lunas)</div>
                    <div class="summary-value">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom: 10px; color: #444;">Rincian Transaksi</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Resi</th>
                <th width="20%">Customer</th>
                <th width="15%">Pembayaran</th>
                <th width="15%">Status Order</th>
                <th width="15%" class="text-right">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->resi }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td class="text-center">
                        @if($t->status_pembayaran == 'SUDAH DIBAYAR')
                            <span class="badge badge-success">LUNAS</span>
                        @else
                            <span class="badge badge-warning">PENDING</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $t->status }}</td>
                    <td class="text-right">{{ number_format($t->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>
