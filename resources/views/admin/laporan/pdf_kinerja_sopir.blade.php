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
                <th width="25%">Nama Sopir</th>
                <th width="20%">Kendaraan</th>
                <th width="20%" class="text-center">Total Muatan Selesai</th>
                <th width="15%" class="text-right">Rata-rata/Order</th>
                <th width="15%" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sopirs as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $s->nama }}</strong>
                        <div style="font-size: 10px; color: #666; margin-top: 2px;">HP: {{ $s->no_hp ?? '-' }}</div>
                    </td>
                    <td>
                        @if($s->kendaraan)
                            <strong>{{ $s->kendaraan->no_polisi }}</strong>
                            <div style="font-size: 10px; color: #666; margin-top: 2px;">{{ $s->kendaraan->merk }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        <strong>{{ $s->total_selesai }} Order</strong>
                        <div style="font-size: 10px; color: #666; margin-top: 2px;">
                            {{ number_format(($s->total_berat ?? 0) / 1000, 1, ',', '.') }} Ton
                        </div>
                    </td>
                    <td class="text-right">
                        @if($s->total_selesai > 0)
                            Rp {{ number_format(($s->total_pendapatan ?? 0) / $s->total_selesai, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        Rp {{ number_format($s->total_pendapatan ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @if($s->pesanans->count() > 0)
                <tr>
                    <td></td>
                    <td colspan="5" style="padding: 5px 10px 10px 10px; background-color: #fafafa;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 10px; border: 1px solid #eee;">
                            <thead>
                                <tr style="background-color: #f1f5f9; font-weight: bold; color: #475569;">
                                    <th style="padding: 4px 8px; border: 1px solid #e2e8f0; font-size: 9px; text-align: left;">No. Resi</th>
                                    <th style="padding: 4px 8px; border: 1px solid #e2e8f0; font-size: 9px; text-align: left;">Tanggal Selesai</th>
                                    <th style="padding: 4px 8px; border: 1px solid #e2e8f0; font-size: 9px; text-align: left;">Pabrik/Kustomer</th>
                                    <th style="padding: 4px 8px; border: 1px solid #e2e8f0; font-size: 9px; text-align: center;">Muatan</th>
                                    <th style="padding: 4px 8px; border: 1px solid #e2e8f0; font-size: 9px; text-align: right;">Omzet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($s->pesanans as $order)
                                    <tr>
                                        <td style="padding: 4px 8px; border: 1px solid #e2e8f0; color: #0284c7; font-weight: bold;">{{ $order->resi }}</td>
                                        <td style="padding: 4px 8px; border: 1px solid #e2e8f0; color: #64748b;">{{ $order->tanggal_selesai ? $order->tanggal_selesai->format('d/m/Y H:i') : '-' }}</td>
                                        <td style="padding: 4px 8px; border: 1px solid #e2e8f0;">{{ $order->nama_pabrik }}</td>
                                        <td style="padding: 4px 8px; border: 1px solid #e2e8f0; text-align: center;">{{ number_format($order->berat / 1000, 1, ',', '.') }} Ton</td>
                                        <td style="padding: 4px 8px; border: 1px solid #e2e8f0; text-align: right; color: #16a34a; font-weight: bold;">Rp {{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kinerja sopir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>
