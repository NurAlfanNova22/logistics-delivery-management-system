@extends('layouts.admin')
@section('page-title', 'Data Pesanan')

@section('content')
<div>
    {{-- Toolbar --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center bg-white p-2 rounded-3 shadow-sm border w-100">
            <div class="input-group input-group-sm" style="width: 200px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="resi" value="{{ request('resi') }}" placeholder="Cari Resi..." class="form-control border-start-0" onchange="this.form.submit()">
            </div>

            <div class="input-group input-group-sm" style="width: 200px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-filter text-muted"></i></span>
                <select name="status" onchange="this.form.submit()" class="form-select border-start-0">
                    <option value="">Semua Status</option>
                    <option value="MENUNGGU KONFIRMASI" {{ request('status') == 'MENUNGGU KONFIRMASI' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                    <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="input-group input-group-sm" style="width: 200px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-cash-coin text-muted"></i></span>
                <select name="status_pembayaran" onchange="this.form.submit()" class="form-select border-start-0">
                    <option value="">Semua Pembayaran</option>
                    <option value="BELUM DIBAYAR" {{ request('status_pembayaran') == 'BELUM DIBAYAR' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="SUDAH DIBAYAR" {{ request('status_pembayaran') == 'SUDAH DIBAYAR' ? 'selected' : '' }}>Sudah Bayar / Lunas</option>
                </select>
            </div>

            <div class="input-group input-group-sm" style="width: 210px;">
                <span class="input-group-text bg-light border-end-0" title="Tanggal Pembuatan Pesanan"><i class="bi bi-calendar3 text-muted"></i> Tgl Pesan</span>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="form-control border-start-0">
            </div>

            <div class="input-group input-group-sm" style="width: 210px;">
                <span class="input-group-text bg-light border-end-0" title="Tanggal Pesanan Tiba/Selesai"><i class="bi bi-calendar-check text-muted"></i> Tgl Selesai</span>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" onchange="this.form.submit()" class="form-control border-start-0">
            </div>

            @if(request('status') || request('tanggal') || request('resi') || request('tanggal_sampai'))
                <a href="{{ route('pesanan.index') }}" class="btn btn-sm btn-outline-secondary px-3 ms-auto">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Tanggal</th>
                        <th>Resi</th>
                        <th>Pengirim</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Status Pengiriman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $item)
                    <tr>
                        <td class="ps-4 text-muted">{{ $item->id }}</td>
                        <td>
                            <span class="fw-semibold" style="font-size:13px">{{ $item->created_at->format('d M Y') }}</span><br>
                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ $item->resi ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->nama_pabrik }}</span><br>
                            <small class="text-muted">{{ Str::limit($item->alamat_asal, 35) }}</small>
                        </td>
                        <td>
                            <small>{{ Str::limit($item->alamat_tujuan, 35) }}</small>
                        </td>
                        <td>
                            <span class="badge
                                @if(str_contains(strtolower($item->status), 'menunggu')) text-bg-warning
                                @elseif(strtolower($item->status) === 'aktif') text-bg-primary
                                @elseif(strtolower($item->status) === 'dibatalkan') text-bg-danger
                                @else text-bg-success
                                @endif">
                                {{ $item->status }}
                            </span>
                            @if(isset($item->status_pembayaran))
                                <br>
                                <span class="badge {{ strtoupper($item->status_pembayaran) == 'SUDAH DIBAYAR' ? 'text-bg-success' : 'text-bg-danger' }} mt-1" style="font-size: 10px;">
                                    <i class="bi {{ strtoupper($item->status_pembayaran) == 'SUDAH DIBAYAR' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                    {{ rtrim(str_replace('BELUM DIBAYAR', 'BLM BAYAR', $item->status_pembayaran)) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_pengiriman)
                                <span class="badge text-bg-info">{{ $item->status_pengiriman }}</span>
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pesanan.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                @if(str_contains(strtolower($item->status), 'menunggu') && !$item->sopir_id)
                                    <a href="{{ route('pesanan.assignForm', $item->id) }}"
                                        class="btn btn-sm btn-primary" title="Assign Sopir">
                                        <i class="bi bi-person-check-fill"></i>
                                    </a>
                                @endif

                                @if(strtolower($item->status) !== 'selesai')
                                    <a href="{{ route('pesanan.updateStatusForm', $item->id) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edit Status">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4"></i>
                            Tidak ada data pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $pesanan->links() }}
    </div>
</div>
@endsection