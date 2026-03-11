@extends('layouts.admin')

@section('content')

<h4>Tambah Sopir</h4>

<form method="POST" action="/admin/sopir">
@csrf

<div class="mb-2">
    <input type="text" 
           class="form-control" 
           name="nama" 
           placeholder="Nama Sopir" 
           required>
</div>

<div class="mb-2">
    <input type="email" 
           class="form-control" 
           name="email" 
           placeholder="Email Login" 
           required>
</div>

<div class="mb-2">
    <input type="password" 
           class="form-control" 
           name="password" 
           placeholder="Password Login" 
           required>
</div>

<div class="mb-2">
    <input type="text" 
           class="form-control" 
           name="no_hp" 
           placeholder="No HP" 
           required>
</div>

<div class="mb-2">
    <textarea class="form-control" 
              name="alamat" 
              placeholder="Alamat" 
              required></textarea>
</div>

<div class="mb-3">
    <label>Kendaraan Utama</label>
    <select class="form-control" name="kendaraan_id">
        <option value="">-- Pilih Kendaraan --</option>

        @foreach($kendaraans as $k)
        <option value="{{ $k->id }}">
            {{ $k->no_polisi }} - {{ $k->merk }} ({{ $k->jenis }})
        </option>
        @endforeach

    </select>
</div>

<button class="btn btn-success">Simpan</button>
<a href="/admin/sopir" class="btn btn-secondary">Kembali</a>

</form>

@endsection