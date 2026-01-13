@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Karyawan')

@section('content')
<!-- Welcome Card -->
<div class="welcome-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0">Semoga hari Anda menyenangkan dan produktif</p>
        </div>
        <div class="col-md-4 text-end">
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> {{ now()->locale('id')->isoFormat('D MMMM Y') }}</h5>
            <p class="mb-0">{{ now()->locale('id')->isoFormat('dddd') }}</p>
        </div>
    </div>
</div>

<!-- Quick Attendance -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="chart-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-3">
                        <i class="bi bi-fingerprint me-2"></i>
                        Absensi Hari Ini
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                    <i class="bi bi-box-arrow-in-right fs-4"></i>
                                </div>
                                <div>
                                    <small class="opacity-75">Jam Masuk</small>
                                    <h3 class="mb-0 fw-bold">
                                        {{ $todayAttendance && $todayAttendance->time_in ? substr($todayAttendance->time_in, 0, 5) : '--:--' }} WIB
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                    <i class="bi bi-box-arrow-right fs-4"></i>
                                </div>
                                <div>
                                    <small class="opacity-75">Jam Keluar</small>
                                    <h3 class="mb-0 fw-bold">
                                        {{ $todayAttendance && $todayAttendance->time_out ? substr($todayAttendance->time_out, 0, 5) : '--:--' }} WIB
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @if(!$todayAttendance || !$todayAttendance->time_in)
                        <form action="{{ route('karyawan.clock-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light btn-lg px-5 py-3 mb-2 w-100">
                                <i class="bi bi-fingerprint me-2"></i>
                                Clock In
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-light btn-lg px-5 py-3 mb-2 w-100" disabled>
                            <i class="bi bi-check-circle me-2"></i>
                            Sudah Clock In
                        </button>
                    @endif

                    @if($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out)
                        <form action="{{ route('karyawan.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light px-5 py-3 w-100">
                                <i class="bi bi-fingerprint me-2"></i>
                                Clock Out
                            </button>
                        </form>
                    @elseif($todayAttendance && $todayAttendance->time_out)
                        <button class="btn btn-outline-light px-5 py-3 w-100" disabled>
                            <i class="bi bi-check-circle me-2"></i>
                            Sudah Clock Out
                        </button>
                    @else
                        <button class="btn btn-outline-light px-5 py-3 w-100" disabled>
                            <i class="bi bi-fingerprint me-2"></i>
                            Clock Out
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-success">
            <div class="icon-box">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-label">Kehadiran Bulan Ini</div>
            <div class="stat-value">{{ $stats['hadir'] }}/{{ $stats['total_days'] }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Keterlambatan</div>
            <div class="stat-value">{{ $stats['terlambat'] }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-label">Izin/Sakit</div>
            <div class="stat-value">{{ $stats['izin'] }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-label">Lembur Disetujui</div>
            <div class="stat-value">{{ $overtimeStats['approved'] }}</div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="row g-4">
    <!-- Attendance History -->
    <div class="col-xl-8">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    Riwayat Absensi 7 Hari Terakhir
                </h5>
                <a href="{{ route('karyawan.attendances') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-calendar-range me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Total Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
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
                                    {{ $diff->h }} jam {{ $diff->i }} menit
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->status === 'hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($attendance->status === 'terlambat')
                                    <span class="badge bg-warning">Terlambat</span>
                                @elseif($attendance->status === 'izin')
                                    <span class="badge bg-info">Izin</span>
                                @elseif($attendance->status === 'sakit')
                                    <span class="badge bg-secondary">Sakit</span>
                                @elseif($attendance->status === 'cuti')
                                    <span class="badge bg-primary">Cuti</span>
                                @else
                                    <span class="badge bg-danger">Alpha</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Profile & Payslip -->
    <div class="col-xl-4">
        <!-- Profile Card -->
        <div class="chart-card mb-4">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    Profil Saya
                </h5>
            </div>
            <div class="profile-info text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                    <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $employee->user->name }}</h5>
                <p class="text-muted mb-3">{{ $employee->position->title }} - {{ $employee->department->name }}</p>
                
                <div class="row g-2 text-start">
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">NIP</small>
                                <span class="fw-semibold">{{ $employee->nip }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <span class="fw-semibold">{{ $employee->user->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <span class="fw-semibold">{{ $employee->phone_number ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('karyawan.profile') }}" class="btn btn-outline-primary w-100 mt-3">
                    <i class="bi bi-pencil me-2"></i>
                    Lihat Profil
                </a>
            </div>
        </div>

        <!-- Latest Payslip -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Slip Gaji Terakhir
                </h5>
            </div>
            <div class="payslip-info">
                @if($latestPayroll)
                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Periode</span>
                        <span class="fw-bold">{{ $latestPayroll->month }} {{ $latestPayroll->year }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Gaji Pokok</span>
                        <span class="fw-bold">Rp {{ number_format($latestPayroll->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tunjangan</span>
                        <span class="fw-bold text-success">Rp {{ number_format($latestPayroll->total_allowance, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Potongan</span>
                        <span class="fw-bold text-danger">Rp {{ number_format($latestPayroll->total_deduction, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total Gaji</span>
                        <h5 class="mb-0 text-primary">Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}</h5>
                    </div>
                </div>
                <a href="{{ route('karyawan.payslips.download', $latestPayroll->id) }}" class="btn btn-primary w-100">
                    <i class="bi bi-download me-2"></i>
                    Download Slip Gaji
                </a>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    <p class="mb-0">Belum ada data slip gaji</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-lightning-charge text-primary me-2"></i>
                    Menu Cepat
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('karyawan.payslips') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-receipt d-block fs-2 mb-2"></i>
                        Slip Gaji
                    </a>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-warning w-100 py-3" data-bs-toggle="modal" data-bs-target="#overtimeModal">
                        <i class="bi bi-clock-history d-block fs-2 mb-2"></i>
                        Ajukan Lembur
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('karyawan.attendances') }}" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-calendar-week d-block fs-2 mb-2"></i>
                        Riwayat Absensi
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('karyawan.overtimes') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-list-check d-block fs-2 mb-2"></i>
                        Daftar Lembur
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overtime Modal -->
<div class="modal fade" id="overtimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    Ajukan Lembur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('karyawan.overtimes.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" required placeholder="Jelaskan pekerjaan lembur yang dilakukan..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Pengajuan lembur akan direview oleh HRD. Pastikan data yang diisi sudah benar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection