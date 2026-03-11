@extends('layouts.admin')

@section('content')
<h4>Tambah Kendaraan</h4>

<form method="POST" action="/admin/kendaraan">
@csrf
<input class="form-control mb-2" name="no_polisi" placeholder="No Polisi">
<input class="form-control mb-2" name="jenis" placeholder="Jenis Kendaraan">
<input class="form-control mb-2" name="merk" placeholder="Merk">

<select class="form-control mb-3" name="sopir_id">
    <option value="">-- Pilih Sopir --</option>
    @foreach($sopirs as $s)
        <option value="{{ $s->id }}">{{ $s->nama }}</option>
    @endforeach
</select>

<button class="btn btn-success">Simpan</button>
<a href="/admin/kendaraan" class="btn btn-secondary">Kembali</a>
</form>
@endsection
