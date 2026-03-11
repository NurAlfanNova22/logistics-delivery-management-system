@extends('layouts.admin')

@section('content')
<h4>Edit Kendaraan</h4>

<form method="POST" action="/admin/kendaraan/{{ $kendaraan->id }}">
@csrf @method('PUT')

<input class="form-control mb-2" name="no_polisi" value="{{ $kendaraan->no_polisi }}">
<input class="form-control mb-2" name="jenis" value="{{ $kendaraan->jenis }}">
<input class="form-control mb-2" name="merk" value="{{ $kendaraan->merk }}">

<select class="form-control mb-3" name="sopir_id">
@foreach($sopirs as $s)
    <option value="{{ $s->id }}" {{ $kendaraan->sopir_id == $s->id ? 'selected' : '' }}>
        {{ $s->nama }}
    </option>
@endforeach
</select>

<button class="btn btn-primary">Update</button>
<a href="/admin/kendaraan" class="btn btn-secondary">Kembali</a>
</form>
@endsection
