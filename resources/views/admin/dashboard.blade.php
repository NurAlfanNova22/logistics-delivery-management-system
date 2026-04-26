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

<div class="row g-4 mt-2">
    {{-- Performa Hari Ini --}}
    <div class="col-md-3">
        <div class="card h-100 border-start border-primary border-4">
            <div class="card-body p-4">
                <p class="stat-label mb-1">Pesanan Hari Ini</p>
                <div class="d-flex align-items-center gap-2">
                    <h3 class="stat-value">{{ $pesananHariIni }}</h3>
                    <span class="badge text-bg-light text-primary">Hari Ini</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-start border-success border-4">
            <div class="card-body p-4">
                <p class="stat-label mb-1">Pendapatan Hari Ini</p>
                <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    {{-- Performa Bulan Ini --}}
    <div class="col-md-3">
        <div class="card h-100 border-start border-info border-4">
            <div class="card-body p-4">
                <p class="stat-label mb-1">Pesanan Bulan Ini</p>
                <div class="d-flex align-items-center gap-2">
                    <h3 class="stat-value">{{ $pesananBulanIni }}</h3>
                    <span class="badge text-bg-light text-info">Bulan Ini</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-start border-warning border-4">
            <div class="card-body p-4">
                <p class="stat-label mb-1">Pendapatan Bulan Ini</p>
                <h4 class="fw-bold mb-0 text-warning">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
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

<div class="row g-4 mt-2">
    {{-- Grafik Pemasukan --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-graph-up-arrow me-2"></i>Tren Pemasukan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="min-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Komposisi Status --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-pie-chart-fill me-2"></i>Komposisi Pesanan</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div style="height: 250px; width: 100%;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    {{-- Grafik Performa Sopir --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-person-check-fill me-2"></i>Top 5 Sopir (Selesai)</h6>
            </div>
            <div class="card-body">
                <canvas id="driverChart" style="min-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik Customer Teraktif --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-building-fill me-2"></i>Top 5 Customer</h6>
            </div>
            <div class="card-body">
                <canvas id="customerChart" style="min-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Revenue Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($pemasukan7Hari);
    
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.label),
            datasets: [{
                label: 'Pemasukan Lunas (Rp)',
                data: revenueData.map(d => d.total),
                borderColor: '#F97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#F97316',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($statusComposition);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#FBBF24', '#60A5FA', '#34D399', '#F87171'],
                hoverOffset: 4,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
            }
        }
    });

    // 3. Driver Performance Chart
    const driverCtx = document.getElementById('driverChart').getContext('2d');
    const driverData = @json($topDrivers);

    new Chart(driverCtx, {
        type: 'bar',
        data: {
            labels: driverData.map(d => d.nama),
            datasets: [{
                label: 'Pesanan Selesai',
                data: driverData.map(d => d.total),
                backgroundColor: '#2563EB',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { display: false } },
                y: { grid: { display: false } }
            }
        }
    });

    // 4. Top Customer Chart
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    const customerData = @json($topCustomers);

    new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: customerData.map(d => d.nama_pabrik),
            datasets: [{
                label: 'Total Pesanan',
                data: customerData.map(d => d.total),
                backgroundColor: '#9333EA',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>

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