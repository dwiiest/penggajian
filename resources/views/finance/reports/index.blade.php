@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Laporan Keuangan Penggajian</h2>
                <p class="text-muted mb-0">Tahun {{ $year }}</p>
            </div>
            <div>
                <form method="GET" action="{{ route('finance.reports.index') }}" class="d-inline-flex gap-2">
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Karyawan</p>
                        <h3 class="fw-bold mb-0">{{ $yearlyData['total_employees'] }}</h3>
                        <small class="text-muted">Tahun {{ $year }}</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Penggajian</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($yearlyData['total_salary'] / 1000000, 1) }}M</h3>
                        <small class="text-muted">Tahun {{ $year }}</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-cash-stack fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Tunjangan</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($yearlyData['total_allowance'] / 1000000, 1) }}M</h3>
                        <small class="text-muted">Tahun {{ $year }}</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-plus-circle fs-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Rata-rata/Bulan</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($yearlyData['total_salary'] / 12 / 1000000, 1) }}M</h3>
                        <small class="text-muted">Per bulan</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-calculator fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Grafik Penggajian Tahunan {{ $year }}</h5>
            </div>
            <div class="card-body">
                <canvas id="yearlyChart" height="60"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">Detail Per Bulan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Bulan</th>
                        <th class="border-0 text-center">Jumlah Karyawan</th>
                        <th class="border-0 text-end">Total Gaji Pokok</th>
                        <th class="border-0 text-end">Total Tunjangan</th>
                        <th class="border-0 text-end">Total Potongan</th>
                        <th class="border-0 text-end">Total Penggajian</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalSalary = 0;
                        $grandTotalAllowance = 0;
                        $grandTotalDeduction = 0;
                        $grandTotalEmployees = 0;
                    @endphp
                    @foreach($months as $month)
                        @php
                            $data = $monthlyData[$month];
                            $grandTotalSalary += $data['total_salary'];
                            $grandTotalAllowance += $data['total_allowance'];
                            $grandTotalDeduction += $data['total_deduction'];
                            $grandTotalEmployees += $data['total_employees'];
                        @endphp
                        <tr>
                            <td class="fw-semibold">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                {{ $month }}
                            </td>
                            <td class="text-center">{{ $data['total_employees'] }}</td>
                            <td class="text-end">
                                Rp {{ number_format($data['total_salary'] - $data['total_allowance'] + $data['total_deduction'], 0, ',', '.') }}
                            </td>
                            <td class="text-end text-success">
                                Rp {{ number_format($data['total_allowance'], 0, ',', '.') }}
                            </td>
                            <td class="text-end text-danger">
                                Rp {{ number_format($data['total_deduction'], 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold">
                                Rp {{ number_format($data['total_salary'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr class="fw-bold">
                        <td class="py-3">TOTAL {{ $year }}</td>
                        <td class="text-center py-3">{{ $grandTotalEmployees }}</td>
                        <td class="text-end py-3">
                            Rp {{ number_format($grandTotalSalary - $grandTotalAllowance + $grandTotalDeduction, 0, ',', '.') }}
                        </td>
                        <td class="text-end text-success py-3">
                            Rp {{ number_format($grandTotalAllowance, 0, ',', '.') }}
                        </td>
                        <td class="text-end text-danger py-3">
                            Rp {{ number_format($grandTotalDeduction, 0, ',', '.') }}
                        </td>
                        <td class="text-end py-3">
                            Rp {{ number_format($grandTotalSalary, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Chart data
    const monthLabels = @json($months);
    const chartData = @json(array_values(array_map(function($data) { return $data['total_salary']; }, $monthlyData)));
    const allowanceData = @json(array_values(array_map(function($data) { return $data['total_allowance']; }, $monthlyData)));

    // Create chart
    const ctx = document.getElementById('yearlyChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Total Penggajian',
                    data: chartData,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 2,
                },
                {
                    label: 'Total Tunjangan',
                    data: allowanceData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
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
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush