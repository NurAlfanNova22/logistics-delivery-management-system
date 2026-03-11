@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Update Status Pesanan</h4>

    <form action="{{ route('pesanan.updateStatus', $pesanan->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="menunggu" {{ $pesanan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="aktif" {{ $pesanan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark">Simpan</button>
        <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection