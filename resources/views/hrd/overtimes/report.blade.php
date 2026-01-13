@extends('layouts.app')

@section('title', 'Laporan Lembur')
@section('page-title', 'Laporan Lembur')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Laporan Lembur</h4>
                <p class="text-muted mb-0">
                    Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hrd.overtimes.report.export') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overall Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card card-primary">
            <div class="icon-box mb-2">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Total Lembur Disetujui</div>
            <div class="stat-value">{{ $overallStats['total_overtime'] }}</div>
            <small class="text-muted">Kali lembur</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-warning">
            <div class="icon-box mb-2">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-label">Total Jam Lembur</div>
            <div class="stat-value">{{ number_format($overallStats['total_hours'], 1) }}</div>
            <small class="text-muted">Jam</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card card-success">
            <div class="icon-box mb-2">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-label">Total Pembayaran</div>
            <div class="stat-value" style="font-size: 1.5rem;">Rp {{ number_format($overallStats['total_pay'] / 1000000, 1) }}M</div>
            <small class="text-muted">Rp {{ number_format($overallStats['total_pay'], 0, ',', '.') }}</small>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.overtimes.report') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select class="form-select" name="month">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" 
                                    {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select class="form-select" name="year">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Departemen</label>
                    <select class="form-select" name="department">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Report Table -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Karyawan</th>
                            <th width="15%">Departemen</th>
                            <th width="10%" class="text-center">Total Lembur</th>
                            <th width="12%" class="text-center">Total Jam</th>
                            <th width="12%" class="text-center">Rata-rata/Lembur</th>
                            <th width="21%" class="text-center">Total Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $data['employee']->user->name }}</span>
                                        <small class="text-muted">
                                            {{ $data['employee']->nik }} - {{ $data['employee']->position->title }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $data['employee']->department->name }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6">{{ $data['total_overtime'] }}x</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-warning">{{ number_format($data['total_hours'], 1) }} jam</span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $data['total_overtime'] > 0 ? number_format($data['total_hours'] / $data['total_overtime'], 1) : 0 }} jam/lembur
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-success fs-6">
                                    Rp {{ number_format($data['total_pay'], 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada data lembur untuk periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($reportData) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Keseluruhan:</td>
                            <td class="text-center fw-bold">{{ $overallStats['total_overtime'] }}x</td>
                            <td class="text-center fw-bold text-warning">{{ number_format($overallStats['total_hours'], 1) }} jam</td>
                            <td class="text-center">-</td>
                            <td class="text-center fw-bold text-success fs-5">
                                Rp {{ number_format($overallStats['total_pay'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Summary Info -->
<div class="row mt-4">
    <div class="col-12">
        <div class="chart-card bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Keterangan
                    </h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Laporan ini hanya menampilkan lembur yang sudah <strong>disetujui</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Total pembayaran belum termasuk pajak dan potongan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Data dapat diexport ke Excel untuk analisis lebih lanjut
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Ringkasan
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Total Karyawan Lembur:</small>
                        <strong class="ms-2">{{ count($reportData) }} Orang</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Periode:</small>
                        <strong class="ms-2">{{ $startDate->format('d F') }} - {{ $endDate->format('d F Y') }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Rata-rata Jam per Karyawan:</small>
                        <strong class="ms-2">
                            {{ count($reportData) > 0 ? number_format($overallStats['total_hours'] / count($reportData), 1) : 0 }} Jam
                        </strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Rata-rata Pembayaran per Karyawan:</small>
                        <strong class="ms-2 text-success">
                            Rp {{ count($reportData) > 0 ? number_format($overallStats['total_pay'] / count($reportData), 0, ',', '.') : 0 }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    @media print {
        .btn, .chart-card form, .sidebar, .top-navbar {
            display: none !important;
        }
        .main-wrapper {
            margin-left: 0 !important;
        }
        .table {
            font-size: 12px;
        }
    }
</style>
@endpush