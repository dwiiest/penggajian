@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Welcome Card -->
<div class="welcome-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
            <p class="mb-0">Ini adalah ringkasan sistem penggajian Anda hari ini</p>
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
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="stat-label">Total User Role</div>
                <div class="stat-value">{{ $rolesCount ?? 0 }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
            <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-label">Total User</div>
                <div class="stat-value">{{ $usersCount ?? 0 }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
            <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-building"></i>
            </div>
            <div class="stat-label">Total Departemen</div>
                <div class="stat-value">{{ $departmentsCount ?? 0 }}</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
            <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-briefcase"></i>
            </div>
            <div class="stat-label">Total Jabatan</div>
                <div class="stat-value">{{ $positionsCount ?? 0 }}</div>
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
                    Quick Actions
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('admin.user-roles.create') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-shield-plus d-block fs-2 mb-2"></i>
                        Tambah User Role
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-person-plus d-block fs-2 mb-2"></i>
                        Tambah User
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('departments.create') }}" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-building d-block fs-2 mb-2"></i>
                        Tambah Departemen
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('positions.create') }}" class="btn btn-outline-warning w-100 py-3">
                        <i class="bi bi-briefcase d-block fs-2 mb-2"></i>
                        Tambah Jabatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection