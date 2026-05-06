@extends('layouts.admin')
@section('page-title', 'Ubah Sopir (Reassign)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-warning shadow-sm">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="bg-warning-subtle text-warning rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Alihkan Sopir Pesanan</h5>
                    <p class="text-muted small">Resi: {{ $pesanan->resi }} — {{ $pesanan->nama_pabrik }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger pb-0">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-info py-2 px-3 mb-4">
                    <small>
                        <strong>Sopir Saat Ini:</strong> {{ $pesanan->sopir->nama ?? 'Tidak ada' }}<br>
                        <strong>Kendaraan:</strong> {{ $pesanan->kendaraan->no_polisi ?? '-' }}
                    </small>
                </div>

                <form method="POST" action="{{ route('pesanan.reassignStore', $pesanan->id) }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Pilih Sopir Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <select name="sopir_id" class="form-select border-start-0" required>
                                    <option value="">-- Pilih Sopir Baru --</option>
                                    @foreach($sopir as $s)
                                        @php
                                            $disabled = $s->ketersediaan === 'Offline' ? 'disabled' : '';
                                            $statusText = $s->ketersediaan !== 'Tersedia' ? ' (' . $s->ketersediaan . ')' : '';
                                            $selected = $pesanan->sopir_id == $s->id ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $s->id }}" {{ $disabled }} {{ $selected }}>{{ $s->nama }}{{ $statusText }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted mt-1 d-block">Sopir baru akan mendapatkan notifikasi penugasan ini.</small>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-warning px-4 shadow-sm text-dark">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
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
