@extends('layouts.admin')
@section('page-title', 'Detail Customer')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.customer.index') }}" class="btn btn-link link-secondary p-0 mb-3 text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Customer
    </a>
    
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted small mb-3">{{ $customer->email }}</p>
                    <hr>
                    <div class="row text-start mt-3">
                        <div class="col-12 mb-2">
                            <span class="d-block text-muted small">ID Akun</span>
                            <span class="fw-medium">#CUST-{{ $customer->id }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <span class="d-block text-muted small">Bergabung Sejak</span>
                            <span class="fw-medium">{{ $customer->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="col-12">
                            <span class="d-block text-muted small">Total Pesanan</span>
                            <span class="fw-bold text-primary fs-5">{{ $customer->pesanans->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan Kustomer</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No. Resi</th>
                                    <th>Tanggal</th>
                                    <th>Pabrik</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-end">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->pesanans as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $order->resi }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $order->nama_pabrik }}</td>
                                    <td>
                                        <span class="badge @if($order->status == 'SELESAI') bg-success @elseif($order->status == 'DIBATALKAN') bg-danger @else bg-warning @endif rounded-pill px-2">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold">Rp {{ number_format($order->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted small">Belum ada riwayat pesanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
