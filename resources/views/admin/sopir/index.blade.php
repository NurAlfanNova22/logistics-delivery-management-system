@extends('layouts.admin')

@section('content')

<h4>Data Sopir</h4>

<a href="/admin/sopir/create" class="btn btn-primary mb-3">+ Tambah Sopir</a>

<table class="table table-bordered bg-white">
    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Nama</th>
        <th>Email</th>
        <th>No HP</th>
        <th>Kendaraan</th>
        <th>Alamat</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($sopirs as $s)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #eee; display: flex; align-items: center; justify-content: center;">
                @if($s->foto)
                    <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="bi bi-person text-muted" style="font-size: 20px;"></i>
                @endif
            </div>
        </td>
        <td>{{ $s->nama }}</td>

        <td>{{ $s->user->email ?? '-' }}</td>

        <td>{{ $s->no_hp }}</td>

        <td>
            {{ $s->kendaraan->no_polisi ?? '-' }}
            {{ $s->kendaraan->merk ?? '' }}
        </td>

        <td>{{ $s->alamat }}</td>

        <td>
            @if($s->ketersediaan == 'Tersedia')
                <span class="badge bg-success">Tersedia</span>
            @elseif($s->ketersediaan == 'Sedang Bertugas')
                <span class="badge bg-warning text-dark">Sedang Bertugas</span>
            @else
                <span class="badge bg-secondary">Offline</span>
            @endif
        </td>

        <td>
            <a href="/admin/sopir/{{ $s->id }}/edit"
               class="btn btn-warning btn-sm">
               Edit
            </a>

            <form action="/admin/sopir/{{ $s->id }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Yakin hapus sopir ini?')">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">
                    Hapus
                </button>

            </form>
        </td>

    </tr>
    @endforeach

</table>

@endsection