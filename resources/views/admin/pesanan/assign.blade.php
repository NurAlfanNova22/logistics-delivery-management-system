@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Assign Pesanan #{{ $pesanan->id }}</h4>

    <form method="POST"
        action="{{ route('pesanan.assignStore', $pesanan->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Pilih Sopir</label>
            <select name="sopir_id" class="form-select" required>
                <option value="">-- Pilih Sopir --</option>
                @foreach($sopir as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Kendaraan</label>
            <select name="kendaraan_id" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                @foreach($kendaraan as $k)
                    <option value="{{ $k->id }}">
                        {{ $k->no_polisi }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-dark">Assign</button>
    </form>
</div>
@endsection