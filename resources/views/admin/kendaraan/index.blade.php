@extends('layouts.admin')
@section('page-title', 'Data Kendaraan')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <p class="text-muted small mb-0">Manajemen armada kendaraan operasional ekspedisi.</p>
    </div>
    <a href="/admin/kendaraan/create" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-truck-front"></i> Tambah Kendaraan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="card overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Identitas Kendaraan</th>
                        <th>Spesifikasi</th>
                        <th>Driver Terkait</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraans as $k)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; font-size: 18px;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block">{{ $k->no_polisi }}</span>
                                    <small class="text-muted">{{ $k->merk }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge text-bg-info bg-opacity-10 text-info border border-info border-opacity-25 pill px-3">
                                {{ $k->jenis }}
                            </span>
                        </td>
                        <td>
                            @forelse($k->sopirs as $s)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="admin-avatar" style="width: 24px; height: 24px; font-size: 10px;">{{ strtoupper(substr($s->nama, 0, 1)) }}</div>
                                    <span class="small">{{ $s->nama }}</span>
                                </div>
                            @empty
                                <span class="text-muted small italic">Kosong</span>
                            @endforelse
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/admin/kendaraan/{{ $k->id }}/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/admin/kendaraan/{{ $k->id }}" method="POST" class="d-inline delete-confirm-form" data-confirm-message="Seluruh data terkait kendaraan ini akan ikut terhapus.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-truck mb-2 d-block" style="font-size: 2rem; opacity: 0.3;"></i>
                            Belum ada data kendaraan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection