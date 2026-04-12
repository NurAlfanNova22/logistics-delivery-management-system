@extends('layouts.admin')
@section('page-title', 'Update Status Pesanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="bg-light text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Perbarui Status Pesanan</h5>
                    <p class="text-muted small">Update progres pengiriman untuk Pesanan #{{ $pesanan->id }}</p>
                </div>

                <form action="{{ route('pesanan.updateStatus', $pesanan->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Status Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-info-circle text-muted"></i></span>
                            <select name="status" class="form-select border-start-0" required>
                                <option value="MENUNGGU KONFIRMASI" {{ $pesanan->status == 'MENUNGGU KONFIRMASI' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="AKTIF" {{ $pesanan->status == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                                <option value="SELESAI" {{ $pesanan->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection