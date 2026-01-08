@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('page-title', 'Dashboard Manager')

@section('content')
<!-- Welcome Card -->
<div class="welcome-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0">Pantau performa dan kelola tim Anda dengan efektif</p>
        </div>
        <div class="col-md-4 text-end">
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> {{ date('d F Y') }}</h5>
            <p class="mb-0">{{ date('l') }}</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-label">Total Anggota Tim</div>
            <div class="stat-value">28</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-success">
            <div class="icon-box">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-value">26</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-label">Tugas Selesai</div>
            <div class="stat-value">48/52</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-label">Produktivitas Tim</div>
            <div class="stat-value">87%</div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="row g-4">
    <!-- Team Members -->
    <div class="col-xl-8">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-people-fill text-primary me-2"></i>
                    Anggota Tim Saya
                </h5>
                <div>
                    <button class="btn btn-sm btn-light me-2">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-file-earmark-text me-1"></i> Laporan Tim
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status Hari Ini</th>
                            <th>Tugas</th>
                            <th>Performa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">Andi Wijaya</span>
                                        <small class="text-muted">Senior Developer</small>
                                    </div>
                                </div>
                            </td>
                            <td>Staff IT</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 90%">9/10</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span class="fw-bold">4.8</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">Sari Dewi</span>
                                        <small class="text-muted">Developer</small>
                                    </div>
                                </div>
                            </td>
                            <td>Staff IT</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 85%">17/20</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span class="fw-bold">4.6</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">Budi Prasetyo</span>
                                        <small class="text-muted">Junior Developer</small>
                                    </div>
                                </div>
                            </td>
                            <td>Staff IT</td>
                            <td><span class="badge bg-warning">Terlambat</span></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" style="width: 70%">7/10</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span class="fw-bold">4.2</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">Rina Kusuma</span>
                                        <small class="text-muted">UI/UX Designer</small>
                                    </div>
                                </div>
                            </td>
                            <td>Designer</td>
                            <td><span class="badge bg-info">Izin</span></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 95%">19/20</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span class="fw-bold">4.9</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approval Requests & Team Stats -->
    <div class="col-xl-4">
        <div class="chart-card mb-4">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-clipboard-check text-warning me-2"></i>
                    Perlu Persetujuan
                </h5>
            </div>
            <div class="approval-list">
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-calendar-x text-warning"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Pengajuan Cuti</p>
                        <small class="text-muted">3 pengajuan baru</small>
                    </div>
                    <span class="badge bg-warning">3</span>
                </div>

                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-clock-history text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Koreksi Absensi</p>
                        <small class="text-muted">2 koreksi menunggu</small>
                    </div>
                    <span class="badge bg-info">2</span>
                </div>

                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-file-earmark-text text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">Laporan Tugas</p>
                        <small class="text-muted">5 laporan selesai</small>
                    </div>
                    <span class="badge bg-success">5</span>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">
                        <i class="bi bi-eye me-2"></i>
                        Lihat Semua Persetujuan
                    </button>
                </div>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-trophy text-primary me-2"></i>
                    Top Performer Bulan Ini
                </h5>
            </div>
            <div class="performer-list">
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-warning bg-opacity-25 rounded-circle p-2 me-3">
                        <i class="bi bi-trophy-fill text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-bold">Rina Kusuma</p>
                        <small class="text-muted">UI/UX Designer</small>
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-bold">4.9</span>
                            <small class="text-muted ms-2">95% tugas selesai</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="bg-secondary bg-opacity-25 rounded-circle p-2 me-3">
                        <i class="bi bi-trophy-fill text-secondary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-bold">Andi Wijaya</p>
                        <small class="text-muted">Senior Developer</small>
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-bold">4.8</span>
                            <small class="text-muted ms-2">90% tugas selesai</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="bi bi-trophy-fill text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-bold">Sari Dewi</p>
                        <small class="text-muted">Developer</small>
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-bold">4.6</span>
                            <small class="text-muted ms-2">85% tugas selesai</small>
                        </div>
                    </div>
                </div>
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
                        <i class="bi bi-clipboard-check d-block fs-2 mb-2"></i>
                        Setujui Cuti
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-people d-block fs-2 mb-2"></i>
                        Kelola Tim
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-warning w-100 py-3">
                        <i class="bi bi-graph-up-arrow d-block fs-2 mb-2"></i>
                        Laporan Performa
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-calendar-event d-block fs-2 mb-2"></i>
                        Jadwal Tim
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection