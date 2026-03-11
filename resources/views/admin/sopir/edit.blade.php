@extends('layouts.admin')

@section('content')

<h4>Edit Sopir</h4>

<form method="POST" action="/admin/sopir/{{ $sopir->id }}">
@csrf
@method('PUT')

<div class="mb-2">
<input class="form-control"
       name="nama"
       value="{{ $sopir->nama }}"
       placeholder="Nama Sopir"
       required>
</div>

<div class="mb-2">
<input class="form-control"
       name="email"
       value="{{ $sopir->user->email ?? '' }}"
       placeholder="Email Login"
       required>
</div>

<div class="mb-2">
<input class="form-control"
       name="no_hp"
       value="{{ $sopir->no_hp }}"
       placeholder="No HP"
       required>
</div>

<div class="mb-2">
<textarea class="form-control"
          name="alamat"
          placeholder="Alamat"
          required>{{ $sopir->alamat }}</textarea>
</div>

<div class="mb-3">
<label>Kendaraan Utama</label>

<select class="form-control" name="kendaraan_id">

<option value="">-- Pilih Kendaraan --</option>

@foreach($kendaraans as $k)

<option value="{{ $k->id }}"
{{ $sopir->kendaraan_id == $k->id ? 'selected' : '' }}>

{{ $k->no_polisi }} - {{ $k->merk }} ({{ $k->jenis }})

</option>

@endforeach

</select>

</div>

<button class="btn btn-primary">Update</button>
<a href="/admin/sopir" class="btn btn-secondary">Kembali</a>

</form>

@endsection