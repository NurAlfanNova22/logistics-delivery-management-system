@extends('layouts.admin')

@section('content')
<h4>Data Sopir</h4>

<a href="/admin/sopir/create" class="btn btn-primary mb-3">+ Tambah Sopir</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>No HP</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>

    @foreach($sopirs as $s)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $s->nama }}</td>
        <td>{{ $s->no_hp }}</td>
        <td>{{ $s->alamat }}</td>
        <td>
            <a href="/admin/sopir/{{ $s->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
            <form action="/admin/sopir/{{ $s->id }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
