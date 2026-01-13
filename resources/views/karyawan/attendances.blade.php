@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
<!-- Filter Card -->
<div class="chart-card mb-4">
    <form action="{{ route('karyawan.attendances') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('karyawan.attendances') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-primary">
            <div class="stat-label">Total Hari</div>
            <div class="stat-value">{{ $stats['total_days'] }}</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-success">
            <div class="stat-label">Hadir</div>
            <div class="stat-value">{{ $stats['hadir'] }}</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-warning">
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $stats['terlambat'] }}</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-info">
            <div class="stat-label">Izin</div>
            <div class="stat-value">{{ $stats['izin'] }}</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-secondary">
            <div class="stat-label">Sakit</div>
            <div class="stat-value">{{ $stats['sakit'] }}</div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card card-danger">
            <div class="stat-label">Alpha</div>
            <div class="stat-value">{{ $stats['alpha'] }}</div>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-calendar-week text-primary me-2"></i>
            Data Absensi {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM Y') }}
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Total Jam Kerja</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $attendance)
                <tr>
                    <td>{{ $attendances->firstItem() + $index }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->locale('id')->isoFormat('dddd') }}</td>
                    <td>
                        @if($attendance->time_in)
                            <span class="badge {{ $attendance->status === 'terlambat' ? 'bg-warning' : 'bg-success' }}">
                                {{ substr($attendance->time_in, 0, 5) }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($attendance->time_out)
                            <span class="badge bg-secondary">{{ substr($attendance->time_out, 0, 5) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($attendance->time_in && $attendance->time_out)
                            @php
                                $start = \Carbon\Carbon::parse($attendance->time_in);
                                $end = \Carbon\Carbon::parse($attendance->time_out);
                                $diff = $start->diff($end);
                            @endphp
                            {{ $diff->h }}j {{ $diff->i }}m
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($attendance->status === 'hadir')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i> Hadir
                            </span>
                        @elseif($attendance->status === 'terlambat')
                            <span class="badge bg-warning">
                                <i class="bi bi-clock me-1"></i> Terlambat
                            </span>
                        @elseif($attendance->status === 'izin')
                            <span class="badge bg-info">
                                <i class="bi bi-info-circle me-1"></i> Izin
                            </span>
                        @elseif($attendance->status === 'sakit')
                            <span class="badge bg-secondary">
                                <i class="bi bi-heart-pulse me-1"></i> Sakit
                            </span>
                        @elseif($attendance->status === 'cuti')
                            <span class="badge bg-primary">
                                <i class="bi bi-calendar-x me-1"></i> Cuti
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i> Alpha
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($attendance->note)
                            <small class="text-muted">{{ $attendance->note }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada data absensi untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem() }} dari {{ $attendances->total() }} data
            </div>
            <div>
                {{ $attendances->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Info Card -->
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Data absensi ditampilkan berdasarkan periode bulan dan tahun yang dipilih. 
            Gunakan filter di atas untuk melihat data periode lainnya.
        </div>
    </div>
</div>
@endsection