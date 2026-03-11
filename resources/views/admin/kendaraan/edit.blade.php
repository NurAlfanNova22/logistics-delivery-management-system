@extends('layouts.admin')

@section('content')
<h4>Edit Kendaraan</h4>

<form method="POST" action="/admin/kendaraan/{{ $kendaraan->id }}">
@csrf
@method('PUT')

<input class="form-control mb-2"
       name="no_polisi"
       value="{{ $kendaraan->no_polisi }}"
       placeholder="No Polisi"
       required>

<input class="form-control mb-2"
       name="jenis"
       value="{{ $kendaraan->jenis }}"
       placeholder="Jenis Kendaraan"
       required>

<input class="form-control mb-3"
       name="merk"
       value="{{ $kendaraan->merk }}"
       placeholder="Merk"
       required>

<button class="btn btn-primary">Update</button>
<a href="/admin/kendaraan" class="btn btn-secondary">Kembali</a>

</form>
@endsection