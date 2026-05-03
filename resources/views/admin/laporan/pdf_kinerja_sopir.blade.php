<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Sopir - Lancar Ekspedisi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ea580c; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #ea580c; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .data-table th { background-color: #f8f9fa; color: #444; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #999; }
        .rank { font-weight: bold; color: #ea580c; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Lancar Ekspedisi</h1>
        <p>Laporan Kinerja & Produktivitas Sopir</p>
        <p><strong>Periode:</strong> {{ $periode }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="30%">Nama Sopir</th>
                <th width="20%">Kendaraan</th>
                <th width="15%" class="text-center">Pesanan Selesai</th>
                <th width="30%" class="text-right">Total Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sopirs as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $s->nama }}</strong></td>
                    <td>{{ $s->kendaraan->no_polisi ?? '-' }}</td>
                    <td class="text-center">{{ $s->total_selesai }}</td>
                    <td class="text-right">Rp {{ number_format($s->total_pendapatan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data kinerja sopir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>
