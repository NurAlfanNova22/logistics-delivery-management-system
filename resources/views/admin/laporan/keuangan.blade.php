@extends('layouts.admin')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white border-0 shadow-sm">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <span class="d-block text-white-50 small text-uppercase fw-bold letter-spacing-1">Pemasukan Lunas</span>
                    <h2 class="mb-0 fw-bold">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-danger text-white border-0 shadow-sm">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <div>
                    <span class="d-block text-white-50 small text-uppercase fw-bold letter-spacing-1">Tagihan Pending (Unpaid)</span>
                    <h2 class="mb-0 fw-bold">Rp {{ number_format($totalPending, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
        <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-text me-2"></i>Rincian Transaksi</h6>
        
        <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="d-flex align-items-center gap-2">
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
                <a href="{{ route('admin.laporan.keuangan') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
            <div class="vr mx-1 d-none d-md-block text-muted"></div>
            <a href="{{ route('admin.laporan.exportPdf', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-danger px-3" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. Resi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Pabrik</th>
                        <th>Tagihan (Rp)</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $item)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $item->resi }}</td>
                        <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td class="fw-medium">{{ $item->nama_pabrik }}</td>
                        <td class="fw-bold">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                        <td>
                            @if(strtoupper($item->status_pembayaran) == 'SUDAH DIBAYAR')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2">Lunas ✅</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">Belum Bayar</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pesanan.show', $item->id) }}" class="btn btn-sm btn-light border">
                                <i class="bi bi-search"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt-cutoff d-block fs-1 mb-2 opacity-25"></i>
                            Tidak ada data transaksi ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksi->hasPages())
    <div class="card-footer bg-white py-3 border-top">
        {{ $transaksi->links() }}
    </div>
    @endif
</div>
@endsection
