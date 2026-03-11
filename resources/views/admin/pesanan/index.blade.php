@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Data Pesanan</h3>

    <form method="GET" class="mb-3">
        <select name="status" onchange="this.form.submit()" class="form-select w-auto">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="aktif">Aktif</option>
            <option value="selesai">Selesai</option>
        </select>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Resi</th>
                        <th>Pengirim</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Status Pengiriman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $item)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $item->id }}</td>

                        <td>
                            <span class="fw-bold text-primary">
                                {{ $item->resi ?? '-' }}
                            </span>
                        </td>

                        {{-- Pengirim --}}
                        <td>
                            <strong>{{ $item->nama_pabrik }}</strong><br>
                            <small class="text-muted">{{ $item->alamat_asal }}</small>
                        </td>

                        {{-- Tujuan --}}
                        <td>{{ $item->alamat_tujuan }}</td>

                        {{-- Status --}}
                        <td>
                            <span class="badge
                                @if(strtolower($item->status) === 'menunggu') bg-warning
                                @elseif(strtolower($item->status) === 'aktif') bg-primary
                                @else bg-success
                                @endif">
                                {{ ucfirst(strtolower($item->status)) }}
                            </span>
                        </td>

                        <td>
                            @if($item->status_pengiriman)
                                <span class="badge bg-info">
                                    {{ $item->status_pengiriman }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            @if(strtolower($item->status) === 'menunggu')
                                <a href="{{ route('pesanan.assignForm', $item->id) }}"
                                    class="btn btn-sm btn-primary">
                                    Assign
                                </a>
                            @endif

                            @if(strtolower($item->status) !== 'selesai')
                                <a href="{{ route('pesanan.updateStatusForm', $item->id) }}"
                                    class="btn btn-dark btn-sm">
                                    Update Status
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pesanan->links() }}
    </div>
</div>
@endsection