@extends('layouts.app')

@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <a href="{{ route('hrd.employees.index') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-1 fw-bold">{{ $employee->user->name }}</h4>
                    <p class="text-muted mb-0">{{ $employee->position->title }} - {{ $employee->department->name }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hrd.employees.edit', $employee) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <form action="{{ route('hrd.employees.destroy', $employee) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="chart-card text-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-flex mb-3">
                <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $employee->user->name }}</h4>
            <p class="text-muted mb-3">{{ $employee->position->title }}</p>
            
            <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge bg-success">{{ $employee->department->name }}</span>
                <span class="badge bg-{{ $employee->user->status_badge }}">{{ $employee->user->status_label }}</span>
            </div>

            <hr class="my-3">

            <div class="text-start">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope text-primary me-2"></i>
                    <small>{{ $employee->user->email }}</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone text-primary me-2"></i>
                    <small>{{ $employee->phone_number ?? 'Tidak ada data' }}</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-card-text text-primary me-2"></i>
                    <small>NIK: {{ $employee->nik }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-card-checklist text-primary me-2"></i>
                    <small>NIP: {{ $employee->nip }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-building text-primary me-2"></i>
                        Informasi Kepegawaian
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Departemen:</small>
                        <div><span class="badge bg-success">{{ $employee->department->name }}</span></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Jabatan:</small>
                        <div><span class="badge bg-primary">{{ $employee->position->title }}</span></div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Gaji Pokok:</small>
                        <div class="fw-bold">{{ $employee->position->formatted_base_salary }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Tunjangan Transport:</small>
                        <div>{{ 'Rp ' . number_format($employee->position->transport_allowance, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Tunjangan Makan:</small>
                        <div>{{ 'Rp ' . number_format($employee->position->meal_allowance, 0, ',', '.') }}</div>
                    </div>
                    <hr>
                    <div>
                        <small class="text-muted">Total Gaji:</small>
                        <div class="fw-bold text-primary fs-5">{{ $employee->position->formatted_total_salary }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-bank text-success me-2"></i>
                        Informasi Bank
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Nama Bank:</small>
                        <div class="fw-semibold">{{ $employee->bank_name ?? 'Belum diisi' }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Nomor Rekening:</small>
                        <div class="fw-semibold">{{ $employee->account_number ?? 'Belum diisi' }}</div>
                    </div>
                    <hr>
                    <div>
                        <small class="text-muted">Alamat:</small>
                        <div>{{ $employee->address ?? 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history text-warning me-2"></i>
                Riwayat Absensi Terakhir
            </h6>
            
            @if($employee->attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Total Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>
                                @if($attendance->time_in)
                                    <span class="badge bg-{{ $attendance->isLate() ? 'warning' : 'success' }}">
                                        {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($attendance->time_out)
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td><small>{{ $attendance->work_hours ?? '-' }}</small></td>
                            <td>
                                <span class="badge bg-{{ $attendance->status_badge }}">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('hrd.reports.attendance-detail', ['employee' => $employee->id]) }}" 
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan Lengkap
                </a>
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Belum ada data absensi</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Additional Info -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="chart-card bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-calendar-event text-info me-2"></i>
                Informasi Akun
            </h6>
            <div class="mb-2">
                <small class="text-muted">Terdaftar:</small>
                <div>{{ $employee->created_at->format('d F Y') }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Terakhir Update:</small>
                <div>{{ $employee->updated_at->format('d F Y') }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Role User:</small>
                <div><span class="badge bg-info">{{ ucfirst($employee->user->role->name) }}</span></div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-gear text-secondary me-2"></i>
                Quick Actions
            </h6>
            <div class="row g-2">
                <div class="col-6 col-md-4">
                    <a href="{{ route('hrd.attendances.create') }}?employee={{ $employee->id }}" 
                       class="btn btn-outline-success w-100">
                        <i class="bi bi-calendar-plus d-block fs-4 mb-1"></i>
                        <small>Input Absensi</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('hrd.reports.attendance-detail', $employee) }}" 
                       class="btn btn-outline-primary w-100">
                        <i class="bi bi-file-earmark-text d-block fs-4 mb-1"></i>
                        <small>Laporan Absensi</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('hrd.employees.edit', $employee) }}" 
                       class="btn btn-outline-warning w-100">
                        <i class="bi bi-pencil d-block fs-4 mb-1"></i>
                        <small>Edit Data</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection