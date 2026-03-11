@extends('layouts.admin')

@section('content')
<h4>Tambah Pesanan</h4>

<form method="POST" action="/admin/pesanan">
@csrf

<input class="form-control mb-2" name="kode_pesanan" placeholder="Kode Pesanan">
<input class="form-control mb-2" name="pengirim" placeholder="Nama Pengirim">
<input class="form-control mb-2" name="penerima" placeholder="Nama Penerima">

<textarea class="form-control mb-2" name="alamat_tujuan" placeholder="Alamat Tujuan"></textarea>

<input type="date" class="form-control mb-2" name="tanggal_kirim">

<select class="form-control mb-2" name="sopir_id">
    <option value="">-- Pilih Sopir --</option>
    @foreach($sopirs as $s)
        <option value="{{ $s->id }}">{{ $s->nama }}</option>
    @endforeach
</select>

<select class="form-control mb-2" name="kendaraan_id">
    <option value="">-- Pilih Kendaraan --</option>
    @foreach($kendaraans as $k)
        <option value="{{ $k->id }}">{{ $k->no_polisi }}</option>
    @endforeach
</select>

<select class="form-control mb-3" name="status">
    <option>Diproses</option>
    <option>Dikirim</option>
    <option>Selesai</option>
</select>

<button class="btn btn-success">Simpan</button>
<a href="/admin/pesanan" class="btn btn-secondary">Kembali</a>
</form>
@endsection
