@extends('layouts.admin')
@section('page-title', 'Tambah Sopir')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="admin-avatar mx-auto mb-3" style="width: 64px; height: 64px; font-size: 24px;">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Registrasi Sopir Baru</h5>
                    <p class="text-muted small">Lengkapi data di bawah untuk menambahkan driver baru ke sistem.</p>
                </div>

                <form method="POST" action="/admin/sopir">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Email Login</label>
                            <input type="email" class="form-control" name="email" placeholder="email@ekspedisi.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nomor WhatsApp/HP</label>
                            <input type="text" class="form-control" name="no_hp" placeholder="0812xxxx" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Alamat Domisili</label>
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat lengkap tempat tinggal..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Kendaraan Utama</label>
                            <select class="form-select" name="kendaraan_id">
                                <option value="">-- Tanpa Kendaraan Utama --</option>
                                @foreach($kendaraans as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->no_polisi }} — {{ $k->merk }} ({{ $k->jenis }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="/admin/sopir" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Data
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