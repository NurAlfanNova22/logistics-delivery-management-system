@extends('layouts.admin')
@section('page-title', 'Kelola Customer')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Daftar Customer Terdaftar</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nama</th>
                        <th>Email</th>
                        <th class="text-center">Total Pesanan</th>
                        <th>Tgl Bergabung</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill"></i>
                                    @endif
                                </div>
                                <span class="fw-bold">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill">
                                {{ $item->pesanans_count }} Pesanan
                            </span>
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.customer.show', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Riwayat">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('admin.customer.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Profil">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.customer.destroy', $item->id) }}" method="POST" class="d-inline delete-confirm-form" data-confirm-message="Seluruh data dan histori pesanan kustomer ini akan ikut terhapus.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Kustomer">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada kustomer terdaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
