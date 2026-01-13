@extends('layouts.app')

@section('title', 'Detail Lembur')
@section('page-title', 'Detail Lembur')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <a href="{{ route('hrd.overtimes.index') }}" class="btn btn-light me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-1 fw-bold">Detail Lembur</h4>
                    <p class="text-muted mb-0">{{ $overtime->employee->user->name }} - {{ $overtime->date->format('d F Y') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if($overtime->status === 'pending')
                    <form action="{{ route('hrd.overtimes.approve', $overtime) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Setujui lembur ini?')">
                            <i class="bi bi-check-circle me-2"></i>Setujui
                        </button>
                    </form>
                    <form action="{{ route('hrd.overtimes.reject', $overtime) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak lembur ini?')">
                            <i class="bi bi-x-circle me-2"></i>Tolak
                        </button>
                    </form>
                    <a href="{{ route('hrd.overtimes.edit', $overtime) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Employee Info -->
    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-person-circle text-primary me-2"></i>
                Informasi Karyawan
            </h6>
            
            <div class="text-center mb-3">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-2">
                    <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $overtime->employee->user->name }}</h5>
                <p class="text-muted mb-0">{{ $overtime->employee->position->title }}</p>
            </div>

            <hr>

            <div class="mb-2">
                <small class="text-muted">NIK:</small>
                <div class="fw-semibold">{{ $overtime->employee->nik }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">NIP:</small>
                <div class="fw-semibold">{{ $overtime->employee->nip }}</div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Departemen:</small>
                <div><span class="badge bg-success">{{ $overtime->employee->department->name }}</span></div>
            </div>
            <div class="mb-2">
                <small class="text-muted">Email:</small>
                <div>{{ $overtime->employee->user->email }}</div>
            </div>
        </div>
    </div>

    <!-- Overtime Details -->
    <div class="col-lg-8">
        <div class="chart-card mb-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history text-warning me-2"></i>
                Detail Lembur
            </h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Tanggal Lembur</small>
                        <div class="fw-bold">{{ $overtime->date->format('d F Y') }}</div>
                        <small class="text-muted">{{ $overtime->date->format('l') }}</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-{{ $overtime->status_badge }} fs-6">
                            {{ $overtime->status_label }}
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Jam Mulai</small>
                        <div class="fw-bold fs-5">
                            <i class="bi bi-clock text-primary me-2"></i>
                            {{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Jam Selesai</small>
                        <div class="fw-bold fs-5">
                            <i class="bi bi-clock-fill text-secondary me-2"></i>
                            {{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Total Jam</small>
                        <div class="fw-bold fs-5 text-warning">
                            <i class="bi bi-hourglass-split me-2"></i>
                            {{ number_format($overtime->total_hours, 2) }} jam
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Tarif per Jam</small>
                        <div class="fw-bold fs-5 text-info">
                            Rp {{ number_format($overtime->hourly_rate, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-primary bg-opacity-10 p-3 rounded border border-primary">
                        <small class="text-muted d-block mb-1">Total Pembayaran</small>
                        <div class="fw-bold fs-4 text-primary">
                            Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if($overtime->description)
                <div class="col-12">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Keterangan Pekerjaan</small>
                        <div>{{ $overtime->description }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Approval Info -->
        @if($overtime->status !== 'pending')
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-check-circle-fill text-{{ $overtime->status === 'approved' ? 'success' : 'danger' }} me-2"></i>
                Informasi Approval
            </h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Disetujui/Ditolak Oleh</small>
                    <div class="fw-semibold">
                        {{ $overtime->approver ? $overtime->approver->name : 'N/A' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block">Tanggal Approval</small>
                    <div class="fw-semibold">
                        {{ $overtime->approved_at ? $overtime->approved_at->format('d F Y H:i') : '-' }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Timeline -->
<div class="row mt-4">
    <div class="col-12">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history text-info me-2"></i>
                Riwayat
            </h6>

            <div class="timeline">
                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2">
                            <i class="bi bi-plus-circle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold">Lembur Dibuat</div>
                        <small class="text-muted">{{ $overtime->created_at->format('d F Y H:i') }}</small>
                    </div>
                </div>

                @if($overtime->updated_at != $overtime->created_at)
                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="bg-warning rounded-circle p-2">
                            <i class="bi bi-pencil text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold">Data Diupdate</div>
                        <small class="text-muted">{{ $overtime->updated_at->format('d F Y H:i') }}</small>
                    </div>
                </div>
                @endif

                @if($overtime->approved_at)
                <div class="d-flex">
                    <div class="me-3">
                        <div class="bg-{{ $overtime->status === 'approved' ? 'success' : 'danger' }} rounded-circle p-2">
                            <i class="bi bi-{{ $overtime->status === 'approved' ? 'check' : 'x' }}-circle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $overtime->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</div>
                        <small class="text-muted">
                            oleh {{ $overtime->approver->name }} - {{ $overtime->approved_at->format('d F Y H:i') }}
                        </small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection