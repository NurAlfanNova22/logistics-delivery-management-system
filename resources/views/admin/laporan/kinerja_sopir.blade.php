@extends('layouts.admin')
@section('page-title', 'Laporan Kinerja Sopir')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-person-badge me-2"></i>Statistik Produktivitas Sopir</h6>
            <small class="text-muted">Periode: {{ $periode }}</small>
        </div>
        
        <form action="{{ route('admin.laporan.kinerja') }}" method="GET" class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="width: 170px;">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-event small"></i></span>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" title="Dari Tanggal">
            </div>
            <span class="text-muted small">s/d</span>
            <div class="input-group input-group-sm" style="width: 170px;">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-check small"></i></span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" title="Sampai Tanggal">
            </div>
            <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
            @if(request('start_date') || request('end_date'))
                <a href="{{ route('admin.laporan.kinerja') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
            <div class="vr mx-1 d-none d-md-block text-muted"></div>
            <a href="{{ route('admin.laporan.kinerja.pdf', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-danger px-3" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="5%">No.</th>
                        <th>Sopir</th>
                        <th>Kendaraan</th>
                        <th class="text-center">Total Muatan Selesai</th>
                        <th class="text-end">Total Omzet (Rp)</th>
                        <th class="text-end">Rata-rata/Order (Rp)</th>
                        <th class="text-center pe-4" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sopirs as $index => $s)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}.</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 44px; height: 44px;">
                                    @if($s->foto)
                                        <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill fs-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">{{ $s->nama }}</span>
                                    <small class="text-muted d-block"><i class="bi bi-telephone-fill me-1 small"></i>{{ $s->no_hp ?? '-' }}</small>
                                    @php
                                        $status = $s->ketersediaan;
                                        $badgeClass = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                                        if ($status == 'Tersedia') {
                                            $badgeClass = 'bg-success-subtle text-success border border-success-subtle';
                                        } elseif ($status == 'Sedang Bertugas') {
                                            $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-0.5 rounded-pill" style="font-size: 10px; display: inline-block;">
                                        {{ $status }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($s->kendaraan)
                                <span class="fw-bold d-block text-primary">{{ $s->kendaraan->no_polisi }}</span>
                                <small class="text-muted">{{ $s->kendaraan->merk }} ({{ $s->kendaraan->tipe ?? 'N/A' }})</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                                {{ $s->total_selesai }} Order
                            </span>
                            <small class="text-muted d-block mt-1">
                                Muatan: <strong>{{ number_format(($s->total_berat ?? 0) / 1000, 1, ',', '.') }} Ton</strong>
                            </small>
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Rp {{ number_format($s->total_pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-end text-muted">
                            @if($s->total_selesai > 0)
                                Rp {{ number_format(($s->total_pendapatan ?? 0) / $s->total_selesai, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <button class="btn btn-sm btn-outline-primary px-3" type="button" data-bs-toggle="collapse" data-bs-target="#orders-{{ $s->id }}" aria-expanded="false" aria-controls="orders-{{ $s->id }}">
                                <i class="bi bi-list-ul me-1"></i> Rincian
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse" id="orders-{{ $s->id }}">
                        <td colspan="7" class="bg-light p-3">
                            <div class="card card-body border-0 shadow-sm p-4">
                                <h6 class="fw-bold mb-3 text-secondary d-flex align-items-center">
                                    <i class="bi bi-file-earmark-spreadsheet me-2 text-primary"></i>
                                    Rincian Transaksi Selesai - {{ $s->nama }}
                                </h6>
                                @if($s->pesanans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered bg-white mb-0 align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="15%">No. Resi</th>
                                                    <th width="20%">Tanggal Selesai</th>
                                                    <th width="25%">Kustomer / Pabrik</th>
                                                    <th>Alamat Tujuan</th>
                                                    <th width="15%" class="text-center">Berat Muatan</th>
                                                    <th width="15%" class="text-end">Omzet (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($s->pesanans as $order)
                                                    <tr>
                                                        <td class="fw-bold text-primary">{{ $order->resi }}</td>
                                                        <td>{{ $order->tanggal_selesai ? $order->tanggal_selesai->format('d M Y, H:i') : '-' }}</td>
                                                        <td class="fw-medium">{{ $order->nama_pabrik }}</td>
                                                        <td class="small text-muted">{{ $order->alamat_tujuan_clean }}</td>
                                                        <td class="text-center">{{ number_format($order->berat / 1000, 1, ',', '.') }} Ton</td>
                                                        <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-muted text-center py-3">
                                        <i class="bi bi-info-circle me-1"></i> Tidak ada rincian transaksi pada periode ini
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x d-block fs-1 mb-2 opacity-25"></i>
                            Tidak ada data kinerja sopir pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
