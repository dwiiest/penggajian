@extends('layouts.app')

@section('title', 'Finance Dashboard')

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
                <p class="text-muted mb-0">Kelola penggajian dan keuangan perusahaan dengan efisien</p>
            </div>
            <div class="col-md-4 text-end">
                <h5 class="mb-1">
                    <i class="bi bi-calendar3 text-primary"></i> 
                    {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                </h5>
                <p class="text-muted mb-0">Periode: {{ $monthName }} {{ $currentYear }}</p>
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
                        <p class="text-muted mb-1 small">Total Gaji Bulan Ini</p>
                        <h3 class="fw-bold mb-0">
                            @if($stats['total_payroll'] >= 1000000000)
                                Rp {{ number_format($stats['total_payroll'] / 1000000000, 2) }}M
                            @elseif($stats['total_payroll'] >= 1000000)
                                Rp {{ number_format($stats['total_payroll'] / 1000000, 2) }}Jt
                            @else
                                Rp {{ number_format($stats['total_payroll'], 0, ',', '.') }}
                            @endif
                        </h3>
                        <small class="text-primary">
                            <i class="bi bi-graph-up"></i> 
                            {{ $stats['total_employees'] }} karyawan
                        </small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-cash-stack fs-3 text-primary"></i>
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
                        <p class="text-muted mb-1 small">Gaji Terbayar</p>
                        <h3 class="fw-bold mb-0">{{ $stats['processed'] }}</h3>
                        <small class="text-success">
                            <i class="bi bi-check-circle-fill"></i> 
                            Selesai
                        </small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
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
                        <p class="text-muted mb-1 small">Menunggu Proses</p>
                        <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-hourglass-split"></i> 
                            Draft
                        </small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-hourglass-split fs-3 text-warning"></i>
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
                        <p class="text-muted mb-1 small">Rata-rata Gaji</p>
                        <h3 class="fw-bold mb-0">
                            @if($stats['average_salary'] > 0)
                                @if($stats['average_salary'] >= 1000000)
                                    Rp {{ number_format($stats['average_salary'] / 1000000, 2) }}Jt
                                @else
                                    Rp {{ number_format($stats['average_salary'], 0, ',', '.') }}
                                @endif
                            @else
                                Rp 0
                            @endif
                        </h3>
                        <small class="text-info">
                            <i class="bi bi-calculator"></i> 
                            Per karyawan
                        </small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-calculator fs-3 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <!-- Payroll Trend Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Tren Penggajian 6 Bulan Terakhir
                </h5>
            </div>
            <div class="card-body">
                <canvas id="payrollTrendChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Department Distribution Chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pie-chart text-success me-2"></i>
                    Distribusi per Departemen
                </h5>
            </div>
            <div class="card-body">
                <canvas id="departmentChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Summary by Department Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-building text-primary me-2"></i>
                Ringkasan Penggajian per Departemen
            </h5>
            <div>
                <a href="{{ route('finance.payrolls.index') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-list-ul me-1"></i> Lihat Semua
                </a>
                <a href="{{ route('finance.payrolls.export', ['month' => $monthName, 'year' => $currentYear]) }}" 
                   class="btn btn-sm btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Departemen</th>
                        <th class="border-0 text-center">Jumlah Karyawan</th>
                        <th class="border-0 text-end">Gaji Pokok</th>
                        <th class="border-0 text-end">Tunjangan</th>
                        <th class="border-0 text-end">Potongan</th>
                        <th class="border-0 text-end">Total Gaji</th>
                        <th class="border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalBasic = 0;
                        $grandTotalAllowance = 0;
                        $grandTotalDeduction = 0;
                        $grandTotalNet = 0;
                        $grandTotalEmployees = 0;

                        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];
                    @endphp

                    @forelse($departmentSummary as $index => $dept)
                        @php
                            $grandTotalBasic += $dept->basic_salary;
                            $grandTotalAllowance += $dept->total_allowance;
                            $grandTotalDeduction += $dept->total_deduction;
                            $grandTotalNet += $dept->net_salary;
                            $grandTotalEmployees += $dept->employee_count;

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
                                    {{ $dept->employee_count }}
                                </span>
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($dept->basic_salary, 0, ',', '.') }}
                            </td>

                            <td class="text-end text-success">
                                Rp {{ number_format($dept->total_allowance, 0, ',', '.') }}
                            </td>

                            <td class="text-end text-danger">
                                Rp {{ number_format($dept->total_deduction, 0, ',', '.') }}
                            </td>

                            <td class="text-end fw-bold">
                                Rp {{ number_format($dept->net_salary, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                @if($dept->paid_count == $dept->employee_count)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Terbayar
                                    </span>
                                @elseif($dept->paid_count > 0)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-hourglass-split me-1"></i>Proses
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-clock me-1"></i>Draft
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data penggajian untuk periode ini</p>
                                <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="bi bi-plus-circle me-1"></i>Generate Payroll
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($departmentSummary->count() > 0)
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td>TOTAL KESELURUHAN</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $grandTotalEmployees }}</span>
                        </td>
                        <td class="text-end">Rp {{ number_format($grandTotalBasic, 0, ',', '.') }}</td>
                        <td class="text-end text-success">Rp {{ number_format($grandTotalAllowance, 0, ',', '.') }}</td>
                        <td class="text-end text-danger">Rp {{ number_format($grandTotalDeduction, 0, ',', '.') }}</td>
                        <td class="text-end text-primary fs-5">Rp {{ number_format($grandTotalNet, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif

            </table>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-warning me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentPayrolls->take(5) as $payroll)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="avatar bg-{{ $payroll->status === 'paid' ? 'success' : 'warning' }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px; min-width: 40px;">
                            <i class="bi bi-{{ $payroll->status === 'paid' ? 'check-circle' : 'hourglass-split' }} text-{{ $payroll->status === 'paid' ? 'success' : 'warning' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0">{{ $payroll->employee->user->name }}</h6>
                                <small class="text-muted">{{ $payroll->updated_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted small mb-1">
                                {{ $payroll->employee->position->title }} - {{ $payroll->employee->department->name }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-success">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                                <span class="badge bg-{{ $payroll->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $payroll->status === 'paid' ? 'Dibayar' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
            @if($recentPayrolls->count() > 0)
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('finance.payrolls.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-lightning text-danger me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary text-start">
                        <i class="bi bi-plus-circle me-2"></i>
                        <strong>Generate Payroll Baru</strong>
                        <small class="d-block text-white-50 mt-1">Buat penggajian untuk periode ini</small>
                    </a>
                    
                    <a href="{{ route('finance.payrolls.index', ['status' => 'draft']) }}" 
                       class="btn btn-outline-warning text-start">
                        <i class="bi bi-hourglass-split me-2"></i>
                        <strong>Proses Payroll Draft</strong>
                        <small class="d-block text-muted mt-1">{{ $stats['pending'] }} payroll menunggu proses</small>
                    </a>
                    
                    <a href="{{ route('finance.payrolls.export', ['month' => $monthName, 'year' => $currentYear]) }}" 
                       class="btn btn-outline-success text-start">
                        <i class="bi bi-file-earmark-excel me-2"></i>
                        <strong>Export Laporan Excel</strong>
                        <small class="d-block text-muted mt-1">Download laporan periode ini</small>
                    </a>
                    
                    <a href="{{ route('finance.reports.index') }}" 
                       class="btn btn-outline-info text-start">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        <strong>Lihat Laporan Tahunan</strong>
                        <small class="d-block text-muted mt-1">Analisis keuangan per tahun</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Payroll Trend Chart
    const trendCtx = document.getElementById('payrollTrendChart');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Total Penggajian',
                data: @json($chartData['data']),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
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

    // Department Distribution Chart
    const deptCtx = document.getElementById('departmentChart');
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: @json($departmentChartData['labels']),
            datasets: [{
                data: @json($departmentChartData['data']),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',   // primary
                    'rgba(34, 197, 94, 0.8)',    // success
                    'rgba(251, 191, 36, 0.8)',   // warning
                    'rgba(14, 165, 233, 0.8)',   // info
                    'rgba(239, 68, 68, 0.8)',    // danger
                    'rgba(107, 114, 128, 0.8)',  // secondary
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 11
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    return {
                                        text: label + ' (' + value + ')',
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                }
            }
        }
    });
</script>
@endpush