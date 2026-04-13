@extends('layouts.admin')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Detil Pesanan --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Informasi Pesanan</h6>
                    <span class="badge border bg-light text-dark">{{ $pesanan->resi }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="d-block text-muted small mb-1">Pabrik Pengirim</span>
                            <span class="fw-semibold">{{ $pesanan->nama_pabrik }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted small mb-1">Tanggal Pesanan</span>
                            <span class="fw-medium">{{ $pesanan->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <span class="d-block text-muted small mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Alamat Asal (Pengambilan)</span>
                            <span class="fw-medium">{{ $pesanan->alamat_asal }}</span>
                        </div>
                        <div class="col-12">
                            <span class="d-block text-muted small mb-1"><i class="bi bi-geo-fill text-primary me-1"></i>Alamat Tujuan (Pengiriman)</span>
                            <span class="fw-medium">{{ $pesanan->alamat_tujuan }}</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="d-block text-muted small mb-1">Muatan / Jenis Barang</span>
                            <span class="fw-medium badge bg-secondary">{{ $pesanan->jenis_barang }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block text-muted small mb-1">Total Berat</span>
                            <span class="fw-bold fs-5">{{ $pesanan->berat }} <small class="text-muted fs-6 fw-normal">kg</small></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Sopir & Kendaraan --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-truck me-2"></i>Unit Armada & Sopir</h6>
                </div>
                <div class="card-body">
                    @if($pesanan->sopir && $pesanan->kendaraan)
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 20px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $pesanan->sopir->nama }}</h6>
                                <span class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $pesanan->sopir->no_hp }}</span>
                            </div>
                        </div>
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="row">
                                <div class="col-6 border-end">
                                    <span class="d-block text-muted small mb-1">Kendaraan</span>
                                    <span class="fw-medium">{{ $pesanan->kendaraan->jenis }}</span>
                                </div>
                                <div class="col-6 ps-3">
                                    <span class="d-block text-muted small mb-1">Plat Nomor</span>
                                    <span class="fw-bold border px-2 py-1 bg-white rounded">{{ $pesanan->kendaraan->plat_nomor }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-emoji-neutral text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">Belum ada Sopir & Armada yang ditugaskan.</span>
                            <div class="mt-3">
                                <a href="{{ route('pesanan.assignForm', $pesanan->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-person-plus-fill me-1"></i> Tentukan Sopir
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status & Checkpoint --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <span class="d-block text-white-50 mb-1">Status Utama</span>
                    <h3 class="fw-bold mb-3">
                        @if(strtolower($pesanan->status) === 'dibatalkan')
                            <span class="badge bg-danger">{{ $pesanan->status }}</span>
                        @else
                            {{ $pesanan->status }}
                        @endif
                    </h3>
                    
                    @if($pesanan->status_pengiriman)
                    <div class="bg-white bg-opacity-10 rounded-3 p-2 mb-0">
                        <span class="d-block text-white-50 small mb-1">Status Pengiriman Pengemudi</span>
                        <span class="fw-semibold">{{ $pesanan->status_pengiriman }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Riwayat Perjalanan (Tracking)</h6>
                </div>
                <div class="card-body">
                    @if($pesanan->checkpoints && $pesanan->checkpoints->count() > 0)
                        <div class="position-relative ms-3 mt-2">
                            <div class="position-absolute h-100 border-start border-2 border-primary" style="left: 7px; top: 10px; z-index: 1;"></div>
                            
                            @foreach($pesanan->checkpoints as $index => $cp)
                                <div class="position-relative mb-4 ps-4 z-index-2">
                                    <div class="position-absolute bg-primary rounded-circle border border-3 border-white" style="width: 16px; height: 16px; left: 0; top: 4px; box-shadow: 0 0 0 2px rgba(13,110,253,0.2);"></div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-dark">{{ $cp->lokasi }}</span>
                                        <span class="badge bg-light text-muted border">{{ $cp->created_at->format('H:i') }}</span>
                                    </div>
                                    <span class="text-muted small d-block mb-1">{{ $cp->created_at->format('d M Y') }}</span>

                                    @if($cp->foto)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $cp->foto) }}" target="_blank" class="text-decoration-none">
                                                <div class="bg-light border rounded p-2 d-inline-flex align-items-center">
                                                    <i class="bi bi-image text-primary me-2"></i>
                                                    <span class="small text-dark">Lihat Bukti Foto</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            
                            <div class="position-relative ps-4 z-index-2 opacity-50">
                                <div class="position-absolute bg-secondary rounded-circle border border-3 border-white" style="width: 12px; height: 12px; left: 2px; top: 6px;"></div>
                                <span class="fw-medium text-dark">Pesanan Dibuat</span>
                                <span class="text-muted small d-block">{{ $pesanan->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                            <span class="text-muted">Riwayat perjalanan belum tersedia.<br>Sopir belum melaporkan checkpoint.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
