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
                        <th class="text-center">Total Pesanan Selesai</th>
                        <th class="text-end">Total Omzet (Rp)</th>
                        <th class="text-end pe-4">Rata-rata/Order (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sopirs as $index => $s)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}.</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                                    @if($s->foto)
                                        <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-bold d-block">{{ $s->nama }}</span>
                                    <small class="text-muted">{{ $s->kendaraan->no_polisi ?? '-' }} ({{ $s->kendaraan->merk ?? 'N/A' }})</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                {{ $s->total_selesai }} Pesanan
                            </span>
                        </td>
                        <td class="text-end fw-bold">
                            Rp {{ number_format($s->total_pendapatan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-end pe-4 text-muted">
                            @if($s->total_selesai > 0)
                                Rp {{ number_format(($s->total_pendapatan ?? 0) / $s->total_selesai, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
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
