@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row g-4">
    <!-- Profile Information -->
    <div class="col-lg-4">
        <div class="chart-card text-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $employee->user->name }}</h4>
            <p class="text-muted mb-3">{{ $employee->position->title }}</p>
            <span class="badge bg-success mb-3">
                <i class="bi bi-check-circle me-1"></i> Aktif
            </span>
            
            <div class="text-start mt-4">
                <h6 class="fw-bold mb-3">Informasi Kontak</h6>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-envelope me-1"></i> Email
                    </small>
                    <span>{{ $employee->user->email }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-telephone me-1"></i> No. Telepon
                    </small>
                    <span>{{ $employee->phone_number ?? '-' }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-geo-alt me-1"></i> Alamat
                    </small>
                    <span>{{ $employee->address ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="col-lg-8">
        <!-- Personal Data -->
        <div class="chart-card mb-4">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-person-vcard text-primary me-2"></i>
                    Data Pribadi
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">NIK</label>
                        <p class="fw-semibold mb-0">{{ $employee->nik }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">NIP</label>
                        <p class="fw-semibold mb-0">{{ $employee->nip }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Nama Lengkap</label>
                        <p class="fw-semibold mb-0">{{ $employee->user->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Email</label>
                        <p class="fw-semibold mb-0">{{ $employee->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Data -->
        <div class="chart-card mb-4">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-briefcase text-primary me-2"></i>
                    Data Kepegawaian
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Departemen</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-primary">{{ $employee->department->name }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Jabatan</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-success">{{ $employee->position->title }}</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Gaji Pokok</label>
                        <p class="fw-semibold mb-0">Rp {{ number_format($employee->position->base_salary, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Tunjangan Transport</label>
                        <p class="fw-semibold mb-0">Rp {{ number_format($employee->position->transport_allowance, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Tunjangan Makan</label>
                        <p class="fw-semibold mb-0">Rp {{ number_format($employee->position->meal_allowance, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Status</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i> Aktif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Account Data -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="bi bi-bank text-primary me-2"></i>
                    Informasi Rekening Bank
                </h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Nama Bank</label>
                        <p class="fw-semibold mb-0">{{ $employee->bank_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted mb-1">Nomor Rekening</label>
                        <p class="fw-semibold mb-0">{{ $employee->account_number ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 500;
}

.info-item p {
    font-size: 1rem;
}
</style>
@endpush
@endsection