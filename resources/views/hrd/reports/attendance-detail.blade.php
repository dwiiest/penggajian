@extends('layouts.app')

@section('title', 'Detail Absensi Karyawan')
@section('page-title', 'Detail Absensi Karyawan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <a href="{{ route('hrd.reports.attendance') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-1 fw-bold">{{ $employee->user->name }}</h4>
                    <p class="text-muted mb-0">
                        Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
                    </p>
                </div>
            </div>
            <button class="btn btn-success" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Cetak
            </button>
        </div>
    </div>
</div>

<!-- Employee Info -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="text-center mb-3">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-2">
                    <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $employee->user->name }}</h5>
                <p class="text-muted mb-0">{{ $employee->position->title }}</p>
            </div>
            <hr>
            <div class="mb-2">
                <small class="text-muted">NIK:</small>
                <div class="fw-semibold">{{ $employee->nik }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">NIP:</small>
                <div class="fw-semibold">{{ $employee->nip }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Departemen:</small>
                <div><span class="badge bg-success">{{ $employee->department->name }}</span></div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Jabatan:</small>
                <div><span class="badge bg-primary">{{ $employee->position->title }}</span></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Statistics -->
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <div class="stat-card card-primary">
                    <div class="icon-box mb-2">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-label">Total Hari</div>
                    <div class="stat-value">{{ $stats['total_days'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card card-success">
                    <div class="icon-box mb-2">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-label">Hadir</div>
                    <div class="stat-value">{{ $stats['hadir'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card card-warning">
                    <div class="icon-box mb-2">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-label">Terlambat</div>
                    <div class="stat-value">{{ $stats['terlambat'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card card-info">
                    <div class="icon-box mb-2">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="stat-label">Izin</div>
                    <div class="stat-value">{{ $stats['izin'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card card-primary">
                    <div class="icon-box mb-2">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div class="stat-label">Sakit</div>
                    <div class="stat-value">{{ $stats['sakit'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card card-danger">
                    <div class="icon-box mb-2">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-label">Alpha</div>
                    <div class="stat-value">{{ $stats['alpha'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Period -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.reports.attendance-detail', $employee) }}" method="GET" class="row g-3">
                <div class="col-md-5">
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
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select class="form-select" name="year">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
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

<!-- Attendance Details Table -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="card-header">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-calendar-week text-primary me-2"></i>
                    Detail Absensi per Hari
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="10%">Hari</th>
                            <th width="12%">Jam Masuk</th>
                            <th width="12%">Jam Keluar</th>
                            <th width="15%">Total Jam Kerja</th>
                            <th width="10%">Status</th>
                            <th width="21%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-semibold">{{ $attendance->date->format('d M Y') }}</span>
                            </td>
                            <td>
                                <small>{{ $attendance->date->format('l') }}</small>
                            </td>
                            <td>
                                @if($attendance->time_in)
                                    <span class="badge bg-{{ $attendance->isLate() ? 'warning' : 'success' }}">
                                        {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}
                                    </span>
                                    @if($attendance->isLate())
                                        <small class="text-danger d-block mt-1">Terlambat</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->time_out)
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->work_hours)
                                    <span class="fw-semibold">{{ $attendance->work_hours }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $attendance->status_badge }}">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $attendance->note ?? '-' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
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

<!-- Summary -->
<div class="row mt-4">
    <div class="col-12">
        <div class="chart-card bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        Ringkasan Kehadiran
                    </h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Total Hari Absensi:</small>
                            <div class="fw-bold fs-5">{{ $stats['total_days'] }} Hari</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tingkat Kehadiran:</small>
                            <div class="fw-bold fs-5 text-success">
                                {{ $stats['total_days'] > 0 ? number_format((($stats['hadir'] + $stats['terlambat']) / $stats['total_days']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Keterlambatan:</small>
                            <div class="fw-bold text-warning">{{ $stats['terlambat'] }}x</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Alpha:</small>
                            <div class="fw-bold text-danger">{{ $stats['alpha'] }}x</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Keterangan
                    </h6>
                    <ul class="list-unstyled small">
                        <li class="mb-1">
                            <span class="badge bg-success">Hadir</span>
                            <span class="ms-2">: Karyawan hadir tepat waktu</span>
                        </li>
                        <li class="mb-1">
                            <span class="badge bg-warning">Terlambat</span>
                            <span class="ms-2">: Hadir setelah pukul 08:00</span>
                        </li>
                        <li class="mb-1">
                            <span class="badge bg-info">Izin</span>
                            <span class="ms-2">: Izin dengan surat</span>
                        </li>
                        <li class="mb-1">
                            <span class="badge bg-primary">Sakit</span>
                            <span class="ms-2">: Sakit dengan surat dokter</span>
                        </li>
                        <li class="mb-1">
                            <span class="badge bg-danger">Alpha</span>
                            <span class="ms-2">: Tidak hadir tanpa keterangan</span>
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
            font-size: 11px;
        }
    }
</style>
@endpush