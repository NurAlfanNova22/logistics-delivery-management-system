@extends('layouts.admin')
@section('page-title', 'Edit Kendaraan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="bg-light text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Edit Informasi Kendaraan</h5>
                    <p class="text-muted small">Perbarui detail teknis atau identitas nomor polisi kendaraan.</p>
                </div>

                <form method="POST" action="/admin/kendaraan/{{ $kendaraan->id }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nomor Polisi</label>
                            <input type="text" class="form-control" name="no_polisi" value="{{ $kendaraan->no_polisi }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Jenis Kendaraan</label>
                            <select class="form-select" name="jenis" required>
                                <option value="CDD Long" {{ $kendaraan->jenis == 'CDD Long' ? 'selected' : '' }}>CDD Long</option>
                                <option value="CDD Box" {{ $kendaraan->jenis == 'CDD Box' ? 'selected' : '' }}>CDD Box</option>
                                <option value="Fuso" {{ $kendaraan->jenis == 'Fuso' ? 'selected' : '' }}>Fuso</option>
                                <option value="Tronton" {{ $kendaraan->jenis == 'Tronton' ? 'selected' : '' }}>Tronton</option>
                                <option value="Wingbox" {{ $kendaraan->jenis == 'Wingbox' ? 'selected' : '' }}>Wingbox</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Merk / Model</label>
                            <input type="text" class="form-control" name="merk" value="{{ $kendaraan->merk }}" required>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="/admin/kendaraan" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection