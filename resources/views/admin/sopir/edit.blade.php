@extends('layouts.admin')
@section('page-title', 'Edit Sopir')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="admin-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 24px; overflow: hidden; border: 2px solid #eee;">
                        @if($sopir->foto)
                            <img src="{{ asset('storage/' . $sopir->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($sopir->nama, 0, 1)) }}
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">Edit Profil Sopir</h5>
                    <p class="text-muted small">Perbarui data informasi driver dan alokasi kendaraan.</p>
                </div>

                <form method="POST" action="/admin/sopir/{{ $sopir->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="{{ $sopir->nama }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Email Login</label>
                            <input type="email" class="form-control" name="email" value="{{ $sopir->user->email ?? '' }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nomor WhatsApp/HP</label>
                            <input type="text" class="form-control" name="no_hp" value="{{ $sopir->no_hp }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Alamat Domisili</label>
                            <textarea class="form-control" name="alamat" rows="3" required>{{ $sopir->alamat }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Kendaraan Utama</label>
                            <select class="form-select" name="kendaraan_id">
                                <option value="">-- Tanpa Kendaraan Utama --</option>
                                @foreach($kendaraans as $k)
                                <option value="{{ $k->id }}" {{ $sopir->kendaraan_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->no_polisi }} — {{ $k->merk }} ({{ $k->jenis }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Ganti Foto Profil (Opsional)</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                            <div class="form-text small">Kosongkan jika tidak ingin mengubah foto.</div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="/admin/sopir" class="btn btn-outline-secondary px-4">Batal</a>
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