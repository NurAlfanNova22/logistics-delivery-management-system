@extends('layouts.admin')

@section('content')
<h4>Data Kendaraan</h4>

<a href="/admin/kendaraan/create" class="btn btn-primary mb-3">+ Tambah Kendaraan</a>

<table class="table table-bordered bg-white">
<tr>
    <th>No</th>
    <th>No Polisi</th>
    <th>Jenis</th>
    <th>Merk</th>
    <th>Sopir</th>
    <th>Aksi</th>
</tr>

@foreach($kendaraans as $k)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $k->no_polisi }}</td>
    <td>{{ $k->jenis }}</td>
    <td>{{ $k->merk }}</td>
    <td>{{ $k->sopir->nama }}</td>
    <td>
        <a href="/admin/kendaraan/{{ $k->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
        <form action="/admin/kendaraan/{{ $k->id }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </td>
</tr>
@endforeach
</table>
@endsection
