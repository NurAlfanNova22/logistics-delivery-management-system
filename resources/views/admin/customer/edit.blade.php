@extends('layouts.admin')
@section('page-title', 'Edit Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="mb-4 text-center">
                    <div class="admin-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 24px; overflow: hidden; border: 2px solid #eee; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        @if($customer->foto)
                            <img src="{{ asset('storage/' . $customer->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-person text-muted"></i>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">Edit Profil Customer</h5>
                    <p class="text-muted small">Kelola data informasi akun kustomer Anda.</p>
                </div>

                <form method="POST" action="{{ route('admin.customer.update', $customer->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $customer->email }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Nomor WhatsApp/HP</label>
                            <input type="text" class="form-control" name="no_hp" value="{{ $customer->no_hp }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required>{{ $customer->alamat }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Foto Profil (Opsional)</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                            <div class="form-text small">Biarkan kosong jika tidak ingin mengubah foto.</div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <div class="d-flex gap-2 justify-content-end pt-2">
                                <a href="{{ route('admin.customer.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
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
