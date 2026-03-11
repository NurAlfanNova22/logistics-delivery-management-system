@extends('layouts.admin')

@section('content')
<h4>Edit Sopir</h4>

<form method="POST" action="/admin/sopir/{{ $sopir->id }}">
@csrf
@method('PUT')

<input class="form-control mb-2" name="nama" value="{{ $sopir->nama }}">
<input class="form-control mb-2" name="no_hp" value="{{ $sopir->no_hp }}">
<textarea class="form-control mb-2" name="alamat">{{ $sopir->alamat }}</textarea>

<button class="btn btn-primary">Update</button>
<a href="/admin/sopir" class="btn btn-secondary">Kembali</a>
</form>
@endsection
