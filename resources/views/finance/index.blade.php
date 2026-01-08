@extends('layouts.app')

@section('title', 'Finance Dashboard')
@section('page-title', 'Dashboard Finance')

@section('content')
<!-- Welcome Card -->
<div class="welcome-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0">Kelola penggajian dan keuangan perusahaan dengan efisien</p>
        </div>
        <div class="col-md-4 text-end">
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> {{ date('d F Y') }}</h5>
            <p class="mb-0">Periode: Januari 2026</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-label">Total Gaji Bulan Ini</div>
            <div class="stat-value">Rp 425.5M</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-success">
            <div class="icon-box">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Gaji Terbayar</div>
            <div class="stat-value">178</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-label">Menunggu Proses</div>
            <div class="stat-value">8</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-calculator"></i>
            </div>
            <div class="stat-label">Rata-rata Gaji</div>
            <div class="stat-value">Rp 2.29M</div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="row g-4">
    <!-- Payroll Summary -->
    <div class="col-xl-12">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-receipt text-primary me-2"></i>
                    Ringkasan Penggajian Bulan Ini
                </h5>
                <div>
                    <button class="btn btn-sm btn-light me-2">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <button class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Departemen</th>
                            <th>Jumlah Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Potongan</th>
                            <th>Total Gaji</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <span class="fw-semibold">IT Development</span>
                                </div>
                            </td>
                            <td>35</td>
                            <td>Rp 105M</td>
                            <td>Rp 35M</td>
                            <td>Rp 10.5M</td>
                            <td class="fw-bold text-primary">Rp 129.5M</td>
                            <td><span class="badge bg-success">Terbayar</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-success"></i>
                                    </div>
                                    <span class="fw-semibold">Human Resource</span>
                                </div>
                            </td>
                            <td>18</td>
                            <td>Rp 45M</td>
                            <td>Rp 18M</td>
                            <td>Rp 6.3M</td>
                            <td class="fw-bold text-success">Rp 56.7M</td>
                            <td><span class="badge bg-success">Terbayar</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-warning"></i>
                                    </div>
                                    <span class="fw-semibold">Sales & Marketing</span>
                                </div>
                            </td>
                            <td>42</td>
                            <td>Rp 84M</td>
                            <td>Rp 42M</td>
                            <td>Rp 12.6M</td>
                            <td class="fw-bold text-warning">Rp 113.4M</td>
                            <td><span class="badge bg-warning">Proses</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-info"></i>
                                    </div>
                                    <span class="fw-semibold">Finance</span>
                                </div>
                            </td>
                            <td>25</td>
                            <td>Rp 62.5M</td>
                            <td>Rp 25M</td>
                            <td>Rp 8.75M</td>
                            <td class="fw-bold text-info">Rp 78.75M</td>
                            <td><span class="badge bg-success">Terbayar</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-building text-danger"></i>
                                    </div>
                                    <span class="fw-semibold">Production</span>
                                </div>
                            </td>
                            <td>66</td>
                            <td>Rp 99M</td>
                            <td>Rp 33M</td>
                            <td>Rp 13.2M</td>
                            <td class="fw-bold text-danger">Rp 118.8M</td>
                            <td><span class="badge bg-success">Terbayar</span></td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Keseluruhan:</td>
                            <td class="fw-bold text-primary fs-5">Rp 497.15M</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
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
                        <i class="bi bi-calculator d-block fs-2 mb-2"></i>
                        Hitung Gaji
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-cash-stack d-block fs-2 mb-2"></i>
                        Proses Pembayaran
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-warning w-100 py-3">
                        <i class="bi bi-file-earmark-spreadsheet d-block fs-2 mb-2"></i>
                        Laporan Keuangan
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-graph-up d-block fs-2 mb-2"></i>
                        Analisis Gaji
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection