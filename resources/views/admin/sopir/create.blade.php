@extends('layouts.admin')

@section('content')
<h4>Tambah Sopir</h4>

<form method="POST" action="/admin/sopir">
@csrf
<input class="form-control mb-2" name="nama" placeholder="Nama Sopir">
<input class="form-control mb-2" name="no_hp" placeholder="No HP">
<textarea class="form-control mb-2" name="alamat" placeholder="Alamat"></textarea>

<button class="btn btn-success">Simpan</button>
<a href="/admin/sopir" class="btn btn-secondary">Kembali</a>
</form>
@endsection
