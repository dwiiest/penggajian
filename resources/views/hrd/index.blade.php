@extends('layouts.app')

@section('title', 'HRD Dashboard')
@section('page-title', 'Dashboard HRD')

@section('content')
<!-- Welcome Card -->
<div class="welcome-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0">Kelola data kepegawaian dan absensi karyawan dengan mudah</p>
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
            <div class="stat-label">Total Karyawan</div>
            <div class="stat-value">186</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-success">
            <div class="icon-box">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-value">178</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">12</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-danger">
            <div class="icon-box">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-label">Tidak Hadir</div>
            <div class="stat-value">8</div>
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
                        <i class="bi bi-person-plus d-block fs-2 mb-2"></i>
                        Tambah Karyawan
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-calendar-check d-block fs-2 mb-2"></i>
                        Input Absensi
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-warning w-100 py-3">
                        <i class="bi bi-file-earmark-text d-block fs-2 mb-2"></i>
                        Laporan Absensi
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-briefcase d-block fs-2 mb-2"></i>
                        Kelola Jabatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection