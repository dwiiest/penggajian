@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Karyawan Aktif</h6>
                    <h3 class="mb-0">{{ $totalKaryawan }}</h3>
                </div>
                <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Karyawan Nonaktif</h6>
                    <h3 class="mb-0">{{ $totalKaryawanNonaktif }}</h3>
                </div>
                <i class="bi bi-person-x" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Total Gaji Bulan Ini</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</h3>
                </div>
                <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-2">Lembur Pending</h6>
                    <h3 class="mb-0">{{ $lemburPending }}</h3>
                </div>
                <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Absensi Hari Ini -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <h4 class="text-success">{{ $absensiHariIni->get('hadir', 0) }}</h4>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div class="col">
                        <h4 class="text-info">{{ $absensiHariIni->get('izin', 0) }}</h4>
                        <small class="text-muted">Izin</small>
                    </div>
                    <div class="col">
                        <h4 class="text-warning">{{ $absensiHariIni->get('sakit', 0) }}</h4>
                        <small class="text-muted">Sakit</small>
                    </div>
                    <div class="col">
                        <h4 class="text-primary">{{ $absensiHariIni->get('cuti', 0) }}</h4>
                        <small class="text-muted">Cuti</small>
                    </div>
                    <div class="col">
                        <h4 class="text-danger">{{ $absensiHariIni->get('alpa', 0) }}</h4>
                        <small class="text-muted">Alpa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Grafik Penggajian Tahun Ini</h5>
            </div>
            <div class="card-body">
                <canvas id="gajiChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(auth()->user()->isAdmin())
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('karyawan.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus"></i> Tambah Karyawan
                        </a>
                    </div>
                    @endif
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('absensi.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-calendar-check"></i> Input Absensi
                        </a>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('gaji.create') }}" class="btn btn-info w-100">
                            <i class="bi bi-calculator"></i> Hitung Gaji
                        </a>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('laporan.index') }}" class="btn btn-warning w-100">
                            <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('gajiChart');
    
    const bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    const dataGaji = @json($gajiPerBulan);
    
    const labels = dataGaji.map(item => bulanNama[item.bulan - 1]);
    const data = dataGaji.map(item => item.total);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Gaji (Rp)',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush