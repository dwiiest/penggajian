@extends('layouts.app')

@section('title', 'Laporan Absensi')
@section('page-title', 'Laporan Absensi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Laporan Absensi</h4>
                <p class="text-muted mb-0">
                    Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hrd.reports.attendance-export') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overall Statistics -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="stat-card card-primary">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="stat-label">Total Absensi</div>
                    <div class="stat-value">{{ $overallStats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-success">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Hadir</div>
                    <div class="stat-value">{{ $overallStats['hadir'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-warning">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <div class="stat-label">Terlambat</div>
                    <div class="stat-value">{{ $overallStats['terlambat'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-info">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-file-text"></i>
                </div>
                <div>
                    <div class="stat-label">Izin</div>
                    <div class="stat-value">{{ $overallStats['izin'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-danger">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Alpha</div>
                    <div class="stat-value">{{ $overallStats['alpha'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.reports.attendance') }}" method="GET" class="row g-3">
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
                            <th width="10%" class="text-center">Total</th>
                            <th width="8%" class="text-center">Hadir</th>
                            <th width="8%" class="text-center">Terlambat</th>
                            <th width="7%" class="text-center">Izin</th>
                            <th width="7%" class="text-center">Sakit</th>
                            <th width="7%" class="text-center">Alpha</th>
                            <th width="8%" class="text-center">Aksi</th>
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
                                <span class="badge bg-primary">{{ $data['total_days'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $data['hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $data['terlambat'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $data['izin'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $data['sakit'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $data['alpha'] }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hrd.reports.attendance-detail', ['employee' => $data['employee']->id, 'month' => $month, 'year' => $year]) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada data absensi untuk periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
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
                <div class="col-md-12">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Keterangan
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-success">Hadir</span>
                            <small class="ms-2">Karyawan hadir tepat waktu</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning">Terlambat</span>
                            <small class="ms-2">Karyawan hadir terlambat</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-info">Izin</span>
                            <small class="ms-2">Karyawan izin dengan surat</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-primary">Sakit</span>
                            <small class="ms-2">Karyawan sakit dengan surat</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-danger">Alpha</span>
                            <small class="ms-2">Tidak hadir tanpa keterangan</small>
                        </li>
                    </ul>
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