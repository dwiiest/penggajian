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
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> {{ date('d F Y') }}</h5>
            <p class="mb-0">{{ date('l') }}</p>
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
                                    <h3 class="mb-0 fw-bold">08:00 WIB</h3>
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
                                    <h3 class="mb-0 fw-bold">--:--</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <button class="btn btn-light btn-lg px-5 py-3 mb-2 w-100">
                        <i class="bi bi-fingerprint me-2"></i>
                        Clock In
                    </button>
                    <button class="btn btn-outline-light px-5 py-3 w-100" disabled>
                        <i class="bi bi-fingerprint me-2"></i>
                        Clock Out
                    </button>
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
            <div class="stat-value">20/22</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Keterlambatan</div>
            <div class="stat-value">2</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-label">Sisa Cuti</div>
            <div class="stat-value">8</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-label">Gaji Bulan Ini</div>
            <div class="stat-value">Rp 8.5M</div>
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
                <a href="#" class="btn btn-sm btn-primary">
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
                        <tr>
                            <td>08 Jan 2026</td>
                            <td>Kamis</td>
                            <td><span class="badge bg-success">08:00</span></td>
                            <td>-</td>
                            <td>-</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                        </tr>
                        <tr>
                            <td>07 Jan 2026</td>
                            <td>Rabu</td>
                            <td><span class="badge bg-success">07:55</span></td>
                            <td><span class="badge bg-secondary">17:05</span></td>
                            <td>9 jam 10 menit</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                        </tr>
                        <tr>
                            <td>06 Jan 2026</td>
                            <td>Selasa</td>
                            <td><span class="badge bg-warning">08:15</span></td>
                            <td><span class="badge bg-secondary">17:00</span></td>
                            <td>8 jam 45 menit</td>
                            <td><span class="badge bg-warning">Terlambat</span></td>
                        </tr>
                        <tr>
                            <td>05 Jan 2026</td>
                            <td>Senin</td>
                            <td><span class="badge bg-success">07:58</span></td>
                            <td><span class="badge bg-secondary">17:02</span></td>
                            <td>9 jam 4 menit</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="6" class="text-center fw-semibold">--- Weekend ---</td>
                        </tr>
                        <tr>
                            <td>02 Jan 2026</td>
                            <td>Kamis</td>
                            <td><span class="badge bg-success">08:02</span></td>
                            <td><span class="badge bg-secondary">17:00</span></td>
                            <td>8 jam 58 menit</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Profile & Payslip -->
    <div class="col-xl-4">
        
        <!-- Latest Payslip -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Slip Gaji Terakhir
                </h5>
            </div>
            <div class="payslip-info">
                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Periode</span>
                        <span class="fw-bold">Desember 2025</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Gaji Pokok</span>
                        <span class="fw-bold">Rp 7.000.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tunjangan</span>
                        <span class="fw-bold text-success">Rp 2.000.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Potongan</span>
                        <span class="fw-bold text-danger">Rp 500.000</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total Gaji</span>
                        <h5 class="mb-0 text-primary">Rp 8.500.000</h5>
                    </div>
                </div>
                <button class="btn btn-primary w-100">
                    <i class="bi bi-download me-2"></i>
                    Download Slip Gaji
                </button>
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
                    <a href="#" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-fingerprint d-block fs-2 mb-2"></i>
                        Absensi
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-receipt d-block fs-2 mb-2"></i>
                        Slip Gaji
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-warning w-100 py-3">
                        <i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
                        Ajukan Cuti
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-clock-history d-block fs-2 mb-2"></i>
                        Riwayat Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection