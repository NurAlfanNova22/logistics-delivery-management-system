@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#FB923C,#EA580C)">
                    <i class="bi bi-box2-fill"></i>
                </div>
                <div>
                    <p class="stat-label">Total Pesanan</p>
                    <h3 class="stat-value">{{ $totalPesanan }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#FCD34D,#D97706)">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div>
                    <p class="stat-label">Menunggu</p>
                    <h3 class="stat-value">{{ $menunggu }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#60A5FA,#2563EB)">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <p class="stat-label">Pesanan Aktif</p>
                    <h3 class="stat-value">{{ $aktif }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#4ADE80,#16A34A)">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <p class="stat-label">Selesai</p>
                    <h3 class="stat-value">{{ $selesai }}</h3>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#C084FC,#9333EA)">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <p class="stat-label">Total Sopir</p>
                    <h3 class="stat-value">{{ $totalSopir }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon" style="background:linear-gradient(135deg,#FB923C,#EA580C)">
                    <i class="bi bi-truck-front-fill"></i>
                </div>
                <div>
                    <p class="stat-label">Total Kendaraan</p>
                    <h3 class="stat-value">{{ $totalKendaraan }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-icon {
    width: 50px; height: 50px; flex-shrink: 0;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 22px;
}
.stat-label {
    font-size: 11.5px; font-weight: 600; text-transform: uppercase;
    letter-spacing: .05em; color: #6B7280; margin-bottom: 2px;
}
.stat-value {
    font-size: 28px; font-weight: 700; color: #111827; margin: 0;
}
</style>
@endsection