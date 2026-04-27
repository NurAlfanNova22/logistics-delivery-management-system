@extends('layouts.admin')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="dashboard-wrapper">
    {{-- Row 1: Hero Stats --}}
    <div class="row g-4 mb-4">
        {{-- Card Pesanan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon-new bg-primary-soft text-primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small fw-medium mb-0">Total Pesanan</p>
                            <h2 class="fw-bold mb-0">{{ number_format($totalPesanan) }}</h2>
                        </div>
                    </div>
                    <div class="d-flex gap-3 pt-3 border-top">
                        <div class="flex-fill">
                            <p class="text-muted small mb-0">Hari Ini</p>
                            <p class="fw-bold text-dark mb-0">+{{ $pesananHariIni }}</p>
                        </div>
                        <div class="flex-fill border-start ps-3">
                            <p class="text-muted small mb-0">Bulan Ini</p>
                            <p class="fw-bold text-dark mb-0">+{{ $pesananBulanIni }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Pendapatan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon-new bg-success-soft text-success">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small fw-medium mb-0">Pendapatan Lunas</p>
                            <h2 class="fw-bold mb-0" style="font-size: 1.5rem">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                    <div class="d-flex gap-3 pt-3 border-top">
                        <div class="flex-fill">
                            <p class="text-muted small mb-0">Hari Ini</p>
                            <p class="fw-bold text-success mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Aset --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon-new bg-purple-soft text-purple">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="text-end">
                            <p class="text-muted small fw-medium mb-0">Total Aset</p>
                            <h2 class="fw-bold mb-0">{{ $totalSopir + $totalKendaraan }}</h2>
                        </div>
                    </div>
                    <div class="d-flex gap-3 pt-3 border-top">
                        <div class="flex-fill">
                            <p class="text-muted small mb-0">Sopir</p>
                            <p class="fw-bold text-dark mb-0">{{ $totalSopir }}</p>
                        </div>
                        <div class="flex-fill border-start ps-3">
                            <p class="text-muted small mb-0">Kendaraan</p>
                            <p class="fw-bold text-dark mb-0">{{ $totalKendaraan }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Main Analysis --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Tren Pemasukan</h5>
                    <p class="text-muted small mb-0">Statistik lunas 7 hari terakhir</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Status Pesanan</h5>
                    <p class="text-muted small mb-0">Komposisi aktif vs selesai</p>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                    <div style="height: 220px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Insights & Rankings --}}
    <div class="row g-4">
        {{-- Top Drivers List --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Sopir Teraktif</h5>
                    <span class="badge bg-light text-dark rounded-pill px-3">Top 5 Selesai</span>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($topDrivers as $index => $driver)
                        <div class="list-group-item px-0 py-3 border-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rank-number bg-light text-muted">{{ $index + 1 }}</div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $driver->nama }}</h6>
                                    <small class="text-muted">Driver Berpengalaman</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary">{{ $driver->total }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">SELESAI</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-muted py-4">Belum ada data performa</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Customers List --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pelanggan Teratas</h5>
                    <span class="badge bg-light text-dark rounded-pill px-3">Total Pesanan</span>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($topCustomers as $index => $customer)
                        <div class="list-group-item px-0 py-3 border-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rank-number bg-light text-muted">{{ $index + 1 }}</div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $customer->nama_pabrik }}</h6>
                                    <small class="text-muted">Customer Loyal</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-purple">{{ $customer->total }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">ORDER</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-muted py-4">Belum ada data transaksi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Revenue Chart (Area Style)
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($pemasukan7Hari);
    
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.label),
            datasets: [{
                label: 'Pendapatan',
                data: revenueData.map(d => d.total),
                borderColor: '#2563EB',
                backgroundColor: (context) => {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return null;
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(37, 99, 235, 0)');
                    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.1)');
                    return gradient;
                },
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2563EB',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { font: { size: 11 }, color: '#9CA3AF' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#9CA3AF' }
                }
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
                backgroundColor: ['#FBBF24', '#3B82F6', '#10B981', '#EF4444'],
                hoverOffset: 10,
                borderWidth: 4,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 8, usePointStyle: true, padding: 20, font: { size: 11 } } }
            }
        }
    });
});
</script>

<style>
/* New Simpler Styles */
.bg-primary-soft { background-color: rgba(37, 99, 235, 0.1); }
.bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
.bg-purple-soft { background-color: rgba(139, 92, 246, 0.1); }
.text-purple { color: #8B5CF6; }

.stat-icon-new {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}

.rank-number {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid rgba(0,0,0,0.03) !important;
}

.card { transition: transform 0.2s ease; }
.card:hover { transform: translateY(-3px); }

h2 { letter-spacing: -0.5px; }
</style>
@endsection