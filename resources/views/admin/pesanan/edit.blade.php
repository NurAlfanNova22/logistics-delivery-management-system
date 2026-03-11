@extends('layouts.admin')

@section('content')
<h4>Edit Pesanan</h4>

<form method="POST" action="/admin/pesanan/{{ $pesanan->id }}">
@csrf @method('PUT')

<input class="form-control mb-2" name="kode_pesanan" value="{{ $pesanan->kode_pesanan }}">
<input class="form-control mb-2" name="pengirim" value="{{ $pesanan->pengirim }}">
<input class="form-control mb-2" name="penerima" value="{{ $pesanan->penerima }}">

<textarea class="form-control mb-2" name="alamat_tujuan">{{ $pesanan->alamat_tujuan }}</textarea>

<input type="date" class="form-control mb-2" name="tanggal_kirim" value="{{ $pesanan->tanggal_kirim }}">

<select class="form-control mb-2" name="sopir_id">
@foreach($sopirs as $s)
<option value="{{ $s->id }}" {{ $pesanan->sopir_id == $s->id ? 'selected' : '' }}>
    {{ $s->nama }}
</option>
@endforeach
</select>

<select class="form-control mb-2" name="kendaraan_id">
@foreach($kendaraans as $k)
<option value="{{ $k->id }}" {{ $pesanan->kendaraan_id == $k->id ? 'selected' : '' }}>
    {{ $k->no_polisi }}
</option>
@endforeach
</select>

<select class="form-control mb-3" name="status">
@foreach(['Diproses','Dikirim','Selesai'] as $st)
<option {{ $pesanan->status == $st ? 'selected' : '' }}>{{ $st }}</option>
@endforeach
</select>

<button class="btn btn-primary">Update</button>
<a href="/admin/pesanan" class="btn btn-secondary">Kembali</a>
</form>
@endsection
