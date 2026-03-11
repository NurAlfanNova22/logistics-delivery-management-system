@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Dashboard Admin</h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6>Total Pesanan</h6>
                    <h3>{{ $totalPesanan }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-primary">
                    <h6>Pesanan Aktif</h6>
                    <h3>{{ $aktif }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-success">
                    <h6>Pesanan Selesai</h6>
                    <h3>{{ $selesai }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-warning">
                    <h6>Menunggu</h6>
                    <h3>{{ $menunggu }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6>Total Sopir</h6>
                    <h3>{{ $totalSopir }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6>Total Kendaraan</h6>
                    <h3>{{ $totalKendaraan }}</h3>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection