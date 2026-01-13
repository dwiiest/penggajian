@extends('layouts.app')

@section('title', 'HRD Dashboard')

@section('content')
<!-- Welcome Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="bi bi-emoji-smile text-warning"></i> 
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-muted mb-0">Kelola data kepegawaian dan absensi karyawan dengan mudah</p>
            </div>
            <div class="col-md-4 text-end">
                <h5 class="mb-1">
                    <i class="bi bi-calendar3 text-primary"></i> 
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </h5>
                <p class="text-muted mb-0">Absensi Hari Ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Karyawan</p>
                        <h3 class="fw-bold mb-0">{{ $totalEmployees }}</h3>
                        <small class="text-primary">
                            <i class="bi bi-people"></i> 
                            Karyawan Aktif
                        </small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-people fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Hadir Hari Ini</p>
                        <h3 class="fw-bold mb-0">{{ $attendanceStats['hadir'] }}</h3>
                        <small class="text-success">
                            <i class="bi bi-check-circle-fill"></i> 
                            Tepat Waktu
                        </small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-person-check fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Terlambat</p>
                        <h3 class="fw-bold mb-0">{{ $attendanceStats['terlambat'] }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-clock-history"></i> 
                            Hari Ini
                        </small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-clock-history fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Tidak Hadir</p>
                        <h3 class="fw-bold mb-0">{{ $attendanceStats['alpha'] + $attendanceStats['izin'] }}</h3>
                        <small class="text-danger">
                            <i class="bi bi-x-circle"></i> 
                            Alpha & Izin
                        </small>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                        <i class="bi bi-x-circle fs-3 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <!-- Attendance Trend Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Tren Absensi 7 Hari Terakhir
                </h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceTrendChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Overtime Stats -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-warning me-2"></i>
                    Statistik Lembur
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Pengajuan</span>
                        <span class="badge bg-primary fs-6">{{ $overtimeStats['total'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Menunggu Approval</span>
                        <span class="badge bg-warning fs-6">{{ $overtimeStats['pending'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" 
                             style="width: {{ $overtimeStats['total'] > 0 ? ($overtimeStats['pending'] / $overtimeStats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Disetujui</span>
                        <span class="badge bg-success fs-6">{{ $overtimeStats['approved'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $overtimeStats['total'] > 0 ? ($overtimeStats['approved'] / $overtimeStats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Ditolak</span>
                        <span class="badge bg-danger fs-6">{{ $overtimeStats['rejected'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" 
                             style="width: {{ $overtimeStats['total'] > 0 ? ($overtimeStats['rejected'] / $overtimeStats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('hrd.overtimes.index', ['status' => 'pending']) }}" 
                       class="btn btn-outline-warning w-100">
                        <i class="bi bi-hourglass-split me-2"></i>
                        Proses Lembur Pending
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Summary -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-building text-primary me-2"></i>
            Ringkasan Absensi per Departemen (Hari Ini)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Departemen</th>
                        <th class="border-0 text-center">Total</th>
                        <th class="border-0 text-center">Hadir</th>
                        <th class="border-0 text-center">Terlambat</th>
                        <th class="border-0 text-center">Izin/Sakit</th>
                        <th class="border-0 text-center">Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];
                    @endphp
                    
                    @forelse($departmentSummary as $index => $dept)
                        @php
                            $color = $colors[$index % count($colors)];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-{{ $color }} bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-{{ $color }}"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $dept->department_name }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                    {{ $dept->total_attendance }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $dept->hadir }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $dept->terlambat }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $dept->izin }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $dept->alpha }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data absensi hari ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Activities & Pending Approvals -->
<div class="row g-3">
    <!-- Recent Attendance -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-success me-2"></i>
                    Absensi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentAttendances as $attendance)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="avatar bg-{{ $attendance->status_badge }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px; min-width: 40px;">
                            <i class="bi bi-person text-{{ $attendance->status_badge }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0">{{ $attendance->employee->user->name }}</h6>
                                <small class="text-muted">{{ $attendance->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted small mb-1">
                                {{ $attendance->employee->position->title }} - {{ $attendance->employee->department->name }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">
                                    <i class="bi bi-clock"></i> 
                                    {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '-' }}
                                    @if($attendance->time_out)
                                        - {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}
                                    @endif
                                </span>
                                <span class="badge bg-{{ $attendance->status_badge }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas absensi</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('hrd.attendances.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Overtime Approvals -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>
                    Lembur Menunggu Approval
                </h5>
            </div>
            <div class="card-body">
                @forelse($pendingOvertimes as $overtime)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="avatar bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px; min-width: 40px;">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0">{{ $overtime->employee->user->name }}</h6>
                                <small class="text-muted">{{ $overtime->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted small mb-1">
                                {{ $overtime->employee->position->title }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">
                                    <i class="bi bi-calendar3"></i> {{ $overtime->date->format('d/m/Y') }}
                                    <i class="bi bi-clock ms-2"></i> {{ $overtime->total_hours }} jam
                                </span>
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('hrd.overtimes.approve', $overtime->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('hrd.overtimes.reject', $overtime->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada lembur pending</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('hrd.overtimes.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-lightning text-danger me-2"></i>
            Menu Cepat
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('hrd.employees.create') }}" class="btn btn-outline-primary w-100 py-3 h-100">
                    <i class="bi bi-person-plus d-block fs-2 mb-2"></i>
                    <strong>Tambah Karyawan</strong>
                    <small class="d-block text-muted mt-1">Registrasi karyawan baru</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hrd.attendances.bulk-create') }}" class="btn btn-outline-success w-100 py-3 h-100">
                    <i class="bi bi-calendar-check d-block fs-2 mb-2"></i>
                    <strong>Input Absensi Bulk</strong>
                    <small class="d-block text-muted mt-1">Isi absensi banyak sekaligus</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hrd.reports.attendance.index') }}" class="btn btn-outline-warning w-100 py-3 h-100">
                    <i class="bi bi-file-earmark-text d-block fs-2 mb-2"></i>
                    <strong>Laporan Absensi</strong>
                    <small class="d-block text-muted mt-1">Rekap & export laporan</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hrd.overtimes.report') }}" class="btn btn-outline-info w-100 py-3 h-100">
                    <i class="bi bi-clock-history d-block fs-2 mb-2"></i>
                    <strong>Laporan Lembur</strong>
                    <small class="d-block text-muted mt-1">Rekap & export lembur</small>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Attendance Trend Chart
    const ctx = document.getElementById('attendanceTrendChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($chartData['hadir']),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Terlambat',
                    data: @json($chartData['terlambat']),
                    borderColor: 'rgb(251, 191, 36)',
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(251, 191, 36)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Izin/Sakit',
                    data: @json($chartData['izin']),
                    borderColor: 'rgb(14, 165, 233)',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(14, 165, 233)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush