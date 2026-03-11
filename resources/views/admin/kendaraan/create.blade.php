@extends('layouts.admin')

@section('content')
<h4>Tambah Kendaraan</h4>

<form method="POST" action="/admin/kendaraan">
@csrf

<input class="form-control mb-2"
       name="no_polisi"
       placeholder="No Polisi"
       required>

<input class="form-control mb-2"
       name="jenis"
       placeholder="Jenis Kendaraan"
       required>

<input class="form-control mb-3"
       name="merk"
       placeholder="Merk"
       required>

<button class="btn btn-success">Simpan</button>
<a href="/admin/kendaraan" class="btn btn-secondary">Kembali</a>

</form>
@endsection