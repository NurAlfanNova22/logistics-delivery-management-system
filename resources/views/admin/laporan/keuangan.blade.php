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
        <!-- Tabs Kontrol -->
        <ul class="nav nav-pills card-header-pills" id="laporanTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active btn-sm" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi" type="button" role="tab" aria-controls="transaksi" aria-selected="true">
                    <i class="bi bi-list-ul me-1"></i> Rincian Transaksi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-sm" id="rekap-tab" data-bs-toggle="tab" data-bs-target="#rekap" type="button" role="tab" aria-controls="rekap" aria-selected="false">
                    <i class="bi bi-people-fill me-1"></i> Rekap per Customer
                </button>
            </li>
        </ul>
        
        <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Filter Customer -->
            <select name="customer_id" class="form-select form-select-sm" style="width: 150px;" title="Pilih Customer">
                <option value="">-- Customer --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Status Pembayaran -->
            <select name="status_pembayaran" class="form-select form-select-sm" style="width: 140px;" title="Status Pembayaran">
                <option value="">-- Status Bayar --</option>
                <option value="SUDAH DIBAYAR" {{ request('status_pembayaran') == 'SUDAH DIBAYAR' ? 'selected' : '' }}>Lunas</option>
                <option value="BELUM DIBAYAR" {{ request('status_pembayaran') == 'BELUM DIBAYAR' ? 'selected' : '' }}>Belum Bayar</option>
            </select>

            <!-- Filter Status Pesanan -->
            <select name="status" class="form-select form-select-sm" style="width: 140px;" title="Status Pesanan">
                <option value="">-- Status Pesanan --</option>
                <option value="MENUNGGU KONFIRMASI" {{ request('status') == 'MENUNGGU KONFIRMASI' ? 'selected' : '' }}>Menunggu</option>
                <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                <option value="DIBATALKAN" {{ request('status') == 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                <option value="DITOLAK" {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <!-- Filter Bulan/Tahun -->
            <select name="month" class="form-select form-select-sm" style="width: 120px;" title="Pilih Bulan">
                <option value="">-- Bulan --</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <select name="year" class="form-select form-select-sm" style="width: 100px;" title="Pilih Tahun">
                <option value="">-- Tahun --</option>
                @for ($y = Carbon\Carbon::now()->year; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <span class="text-muted small">atau</span>

            <!-- Filter Custom Tanggal -->
            <div class="input-group input-group-sm" style="width: 150px;">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-event small"></i></span>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" title="Dari Tanggal">
            </div>
            <span class="text-muted small">s/d</span>
            <div class="input-group input-group-sm" style="width: 150px;">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-check small"></i></span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" title="Sampai Tanggal">
            </div>

            <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
            @if(request('start_date') || request('end_date') || request('month') || request('year') || request('customer_id') || request('status_pembayaran') || request('status'))
                <a href="{{ route('admin.laporan.keuangan') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
            <div class="vr mx-1 d-none d-md-block text-muted"></div>
            <a href="{{ route('admin.laporan.exportPdf', request()->only(['start_date', 'end_date', 'month', 'year', 'customer_id', 'status_pembayaran', 'status'])) }}" class="btn btn-sm btn-danger px-3" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
        </form>
    </div>

    <div class="tab-content" id="laporanTabContent">
        <!-- Tab Rincian Transaksi -->
        <div class="tab-pane fade show active" id="transaksi" role="tabpanel" aria-labelledby="transaksi-tab">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="5%">No.</th>
                                <th>No. Resi</th>
                                <th>Tanggal Transaksi</th>
                                <th>Nama Pabrik</th>
                                <th>Tagihan (Rp)</th>
                                <th>Status Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $index => $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $index + $transaksi->firstItem() }}.</td>
                                <td class="fw-bold text-primary">{{ $item->resi }}</td>
                                <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td class="fw-medium">{{ $item->nama_pabrik }}</td>
                                <td class="fw-bold">Rp {{ number_format($item->total_biaya ?? 0, 0, ',', '.') }}</td>
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
                                <td colspan="7" class="text-center py-5 text-muted">
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

        <!-- Tab Rekap per Customer -->
        <div class="tab-pane fade" id="rekap" role="tabpanel" aria-labelledby="rekap-tab">
            <div class="card-body p-0">
                <div class="p-3 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Menampilkan rekapitulasi akumulasi transaksi per customer berdasarkan filter waktu terpilih.
                    </span>
                    <a href="{{ route('admin.laporan.exportPdf', array_merge(request()->only(['start_date', 'end_date', 'month', 'year']), ['mode' => 'rekap'])) }}" class="btn btn-sm btn-danger px-3" target="_blank">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export Rekap PDF
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="5%">No.</th>
                                <th>Nama Customer</th>
                                <th>Total Pesanan</th>
                                <th>Total Pemasukan Lunas (Rp)</th>
                                <th>Total Piutang (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapCustomer as $index => $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $index + 1 }}.</td>
                                <td class="fw-bold">{{ $item->user->name ?? 'Customer Tidak Diketahui' }}</td>
                                <td><span class="badge bg-secondary">{{ $item->total_pesanan }} Pesanan</span></td>
                                <td class="fw-bold text-success">Rp {{ number_format($item->total_lunas, 0, ',', '.') }}</td>
                                <td class="fw-bold text-danger">Rp {{ number_format($item->total_pending, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.laporan.keuangan', ['customer_id' => $item->user_id] + request()->only(['start_date', 'end_date', 'month', 'year'])) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-funnel-fill me-1"></i> Saring Rincian
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people d-block fs-1 mb-2 opacity-25"></i>
                                    Tidak ada data rekapitulasi customer
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
