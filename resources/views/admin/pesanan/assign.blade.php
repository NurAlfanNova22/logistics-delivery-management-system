@extends('layouts.admin')
@section('page-title', 'Assign Driver')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="bg-light text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Assign Driver & Kendaraan</h5>
                    <p class="text-muted small">Pesanan #{{ $pesanan->id }} — {{ $pesanan->nama_pabrik }}</p>
                </div>

                <form method="POST" action="{{ route('pesanan.assignStore', $pesanan->id) }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Sopir (Driver)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <select name="sopir_id" class="form-select border-start-0" required>
                                    <option value="">-- Pilih Sopir --</option>
                                    @foreach($sopir as $s)
                                        @php
                                            $disabled = $s->ketersediaan === 'Offline' ? 'disabled' : '';
                                            $statusText = $s->ketersediaan !== 'Tersedia' ? ' (' . $s->ketersediaan . ')' : '';
                                        @endphp
                                        <option value="{{ $s->id }}" {{ $disabled }}>{{ $s->nama }}{{ $statusText }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label fw-semibold small text-muted">Kendaraan Armada</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-truck text-muted"></i></span>
                                <select name="kendaraan_id" class="form-select border-start-0" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $k)
                                        <option value="{{ $k->id }}">{{ $k->no_polisi }} ({{ $k->merk }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Konfirmasi & Assign
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