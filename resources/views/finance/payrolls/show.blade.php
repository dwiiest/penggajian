@extends('layouts.app')

@section('title', 'Detail Payroll')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Detail Payroll</h2>
                <p class="text-muted mb-0">{{ $payroll->month }} {{ $payroll->year }}</p>
            </div>
            <div class="btn-group">
                @if($payroll->status !== 'paid')
                <a href="{{ route('finance.payrolls.edit', $payroll->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <form action="{{ route('finance.payrolls.pay', $payroll->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" 
                            onclick="return confirm('Apakah Anda yakin ingin membayar payroll ini?')">
                        <i class="bi bi-cash me-2"></i>Bayar
                    </button>
                </form>
                @else
                <a href="{{ route('finance.payrolls.download-payslip', $payroll->id) }}" 
                   class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download Slip Gaji
                </a>
                @endif
                <a href="{{ route('finance.payrolls.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Employee Info -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Informasi Karyawan
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 80px; height: 80px; font-size: 32px;">
                        {{ substr($payroll->employee->user->name, 0, 1) }}
                    </div>
                    <h5 class="mb-0 fw-bold">{{ $payroll->employee->user->name }}</h5>
                    <p class="text-muted mb-0">{{ $payroll->employee->position->title }}</p>
                </div>

                <hr>

                <div class="row mb-2">
                    <div class="col-5 text-muted">NIK</div>
                    <div class="col-7 fw-semibold">{{ $payroll->employee->nik }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted">NIP</div>
                    <div class="col-7 fw-semibold">{{ $payroll->employee->nip }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted">Departemen</div>
                    <div class="col-7 fw-semibold">{{ $payroll->employee->department->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted">Email</div>
                    <div class="col-7">{{ $payroll->employee->user->email }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted">No. Telepon</div>
                    <div class="col-7">{{ $payroll->employee->phone_number ?? '-' }}</div>
                </div>

                <hr>

                <div class="row mb-2">
                    <div class="col-5 text-muted">Bank</div>
                    <div class="col-7 fw-semibold">{{ $payroll->employee->bank_name ?? '-' }}</div>
                </div>
                <div class="row">
                    <div class="col-5 text-muted">No. Rekening</div>
                    <div class="col-7 fw-semibold">{{ $payroll->employee->account_number ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-calendar-check me-2"></i>Statistik Absensi
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="mb-0 text-success">{{ $attendanceStats['hadir'] }}</h4>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h4 class="mb-0 text-warning">{{ $attendanceStats['terlambat'] }}</h4>
                            <small class="text-muted">Terlambat</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="mb-0 text-info">{{ $attendanceStats['izin'] }}</h4>
                            <small class="text-muted">Izin/Sakit</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="mb-0 text-danger">{{ $attendanceStats['alpha'] }}</h4>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Details -->
    <div class="col-md-8">
        <!-- Salary Summary -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-success text-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-cash-stack me-2"></i>Ringkasan Gaji
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3 border rounded">
                            <h5 class="fw-bold mb-1">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</h5>
                            <small class="text-muted">Gaji Pokok</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded">
                            <h5 class="fw-bold text-success mb-1">Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</h5>
                            <small class="text-muted">Tunjangan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded">
                            <h5 class="fw-bold text-danger mb-1">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</h5>
                            <small class="text-muted">Potongan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-success text-white rounded">
                            <h5 class="fw-bold mb-1">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</h5>
                            <small>Gaji Bersih</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-calculator me-2"></i>Detail Perhitungan
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr class="border-bottom">
                        <td class="py-3">
                            <strong>Gaji Pokok</strong>
                            <br><small class="text-muted">Berdasarkan jabatan</small>
                        </td>
                        <td class="py-3 text-end fw-bold">
                            Rp {{ number_format($breakdown['basic_salary'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="border-bottom bg-light">
                        <td colspan="2" class="py-2">
                            <strong class="text-success">
                                <i class="bi bi-plus-circle me-1"></i>Tunjangan
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 ps-4">
                            Tunjangan Transport
                            <br><small class="text-muted">
                                {{ $attendanceStats['hadir'] }} hari × Rp {{ number_format($payroll->employee->position->transport_allowance, 0, ',', '.') }}
                            </small>
                        </td>
                        <td class="py-2 text-end text-success">
                            Rp {{ number_format($breakdown['transport_allowance'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 ps-4">
                            Tunjangan Makan
                            <br><small class="text-muted">
                                {{ $attendanceStats['hadir'] }} hari × Rp {{ number_format($payroll->employee->position->meal_allowance, 0, ',', '.') }}
                            </small>
                        </td>
                        <td class="py-2 text-end text-success">
                            Rp {{ number_format($breakdown['meal_allowance'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-2 ps-4">
                            Lembur
                            <br><small class="text-muted">
                                {{ $overtimes->count() }} kali lembur, {{ $overtimes->sum('total_hours') }} jam
                            </small>
                        </td>
                        <td class="py-2 text-end text-success">
                            Rp {{ number_format($breakdown['overtime_pay'], 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3">
                            <strong class="text-success">Total Tunjangan</strong>
                        </td>
                        <td class="py-3 text-end fw-bold text-success">
                            Rp {{ number_format($breakdown['total_allowance'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($breakdown['total_deduction'] > 0)
                    <tr class="border-bottom bg-light">
                        <td colspan="2" class="py-2">
                            <strong class="text-danger">
                                <i class="bi bi-dash-circle me-1"></i>Potongan
                            </strong>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="py-3 ps-4">
                            <strong class="text-danger">Total Potongan</strong>
                        </td>
                        <td class="py-3 text-end fw-bold text-danger">
                            Rp {{ number_format($breakdown['total_deduction'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    <tr class="bg-success text-white">
                        <td class="py-3">
                            <strong class="fs-5">GAJI BERSIH</strong>
                        </td>
                        <td class="py-3 text-end">
                            <strong class="fs-5">Rp {{ number_format($breakdown['net_salary'], 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Overtime Details -->
        @if($overtimes->count() > 0)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Rincian Lembur
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Durasi</th>
                                <th>Tarif</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overtimes as $overtime)
                            <tr>
                                <td>{{ $overtime->date->format('d/m/Y') }}</td>
                                <td>{{ $overtime->start_time }} - {{ $overtime->end_time }}</td>
                                <td>{{ $overtime->total_hours }} jam</td>
                                <td>Rp {{ number_format($overtime->hourly_rate, 0, ',', '.') }}/jam</td>
                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="4" class="text-end">Total Lembur:</th>
                                <th class="text-end">
                                    Rp {{ number_format($overtimes->sum('total_pay'), 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Status -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Status Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Status:</strong></p>
                        @if($payroll->status === 'paid')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Sudah Dibayar
                            </span>
                        @else
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-hourglass-split me-1"></i>Belum Dibayar (Draft)
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($payroll->status === 'paid')
                            <p class="mb-1"><strong>Tanggal Pembayaran:</strong></p>
                            <p class="mb-0">{{ $payroll->payment_date->format('d F Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection