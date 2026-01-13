@extends('layouts.app')

@section('title', 'Slip Gaji')
@section('page-title', 'Slip Gaji Saya')

@section('content')
<!-- Filter Card -->
<div class="chart-card mb-4">
    <form action="{{ route('karyawan.payslips') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('karyawan.payslips') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Payslip Grid -->
<div class="row g-4">
    @forelse($payrolls as $payroll)
    <div class="col-lg-4 col-md-6">
        <div class="chart-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $payroll->month }} {{ $payroll->year }}</h5>
                    <span class="badge {{ $payroll->status === 'paid' ? 'bg-success' : ($payroll->status === 'approved' ? 'bg-primary' : 'bg-warning') }}">
                        @if($payroll->status === 'paid')
                            <i class="bi bi-check-circle me-1"></i> Dibayar
                        @elseif($payroll->status === 'approved')
                            <i class="bi bi-check me-1"></i> Disetujui
                        @else
                            <i class="bi bi-clock me-1"></i> Draft
                        @endif
                    </span>
                </div>
                @if($payroll->payment_date)
                    <small class="text-muted">
                        <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d/m/Y') }}
                    </small>
                @endif
            </div>

            <div class="bg-light rounded p-3 mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Gaji Pokok</span>
                    <span class="fw-semibold">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Tunjangan</span>
                    <span class="fw-semibold text-success">+ Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Potongan</span>
                    <span class="fw-semibold text-danger">- Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Gaji Bersih</span>
                    <span class="fw-bold text-primary fs-5">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-info flex-fill" 
                        data-bs-toggle="modal" 
                        data-bs-target="#detailModal{{ $payroll->id }}">
                    <i class="bi bi-eye me-1"></i> Detail
                </button>
                <a href="{{ route('karyawan.payslips.download', $payroll->id) }}" 
                   class="btn btn-sm btn-success flex-fill">
                    <i class="bi bi-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal{{ $payroll->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt me-2"></i>
                        Detail Slip Gaji - {{ $payroll->month }} {{ $payroll->year }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Employee Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Nama</td>
                                    <td>: {{ $employee->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIP</td>
                                    <td>: {{ $employee->nip }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jabatan</td>
                                    <td>: {{ $employee->position->title }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Departemen</td>
                                    <td>: {{ $employee->department->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Periode</td>
                                    <td>: {{ $payroll->month }} {{ $payroll->year }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>: 
                                        <span class="badge {{ $payroll->status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Salary Details -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center bg-primary text-white">RINCIAN GAJI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="60%">Gaji Pokok</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="2" class="fw-bold">TUNJANGAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Total Tunjangan</td>
                                    <td class="text-end text-success fw-bold">Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-danger">
                                    <td colspan="2" class="fw-bold">POTONGAN</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Total Potongan</td>
                                    <td class="text-end text-danger fw-bold">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="fw-bold">GAJI BERSIH</td>
                                    <td class="text-end fw-bold fs-5">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($payroll->payment_date)
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Dibayarkan pada: {{ \Carbon\Carbon::parse($payroll->payment_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('karyawan.payslips.download', $payroll->id) }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="chart-card text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">Belum Ada Data Slip Gaji</h5>
            <p class="text-muted">Slip gaji akan muncul setelah proses penggajian dilakukan oleh Finance</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($payrolls->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $payrolls->withQueryString()->links() }}
</div>
@endif

<!-- Info Card -->
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> 
            Slip gaji dapat diunduh dalam format PDF untuk keperluan administrasi Anda. 
            Simpan slip gaji dengan baik sebagai bukti pembayaran.
        </div>
    </div>
</div>
@endsection