@extends('layouts.app')

@section('title', 'Edit Payroll')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Edit Payroll</h2>
                <p class="text-muted mb-0">{{ $payroll->employee->user->name }} - {{ $payroll->month }} {{ $payroll->year }}</p>
            </div>
            <a href="{{ route('finance.payrolls.show', $payroll->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Form Edit Payroll</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('finance.payrolls.update', $payroll->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="basic_salary" 
                                   class="form-control @error('basic_salary') is-invalid @enderror" 
                                   value="{{ old('basic_salary', $payroll->basic_salary) }}" 
                                   required min="0" step="1000" id="basicSalary">
                        </div>
                        @error('basic_salary')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Tunjangan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total_allowance" 
                                   class="form-control @error('total_allowance') is-invalid @enderror" 
                                   value="{{ old('total_allowance', $payroll->total_allowance) }}" 
                                   required min="0" step="1000" id="totalAllowance">
                        </div>
                        @error('total_allowance')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Termasuk: tunjangan transport, makan, dan lembur
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Potongan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total_deduction" 
                                   class="form-control @error('total_deduction') is-invalid @enderror" 
                                   value="{{ old('total_deduction', $payroll->total_deduction) }}" 
                                   required min="0" step="1000" id="totalDeduction">
                        </div>
                        @error('total_deduction')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Termasuk: BPJS, pajak, pinjaman, dll
                        </small>
                    </div>

                    <hr class="my-4">

                    <div class="bg-light p-3 rounded">
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1 text-muted">Gaji Bersih (Otomatis):</p>
                            </div>
                            <div class="col-6 text-end">
                                <h4 class="mb-0 fw-bold text-success" id="netSalary">
                                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('finance.payrolls.show', $payroll->id) }}" 
                           class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Informasi Karyawan
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 60px; height: 60px; font-size: 24px;">
                        {{ substr($payroll->employee->user->name, 0, 1) }}
                    </div>
                    <h6 class="mb-0">{{ $payroll->employee->user->name }}</h6>
                    <small class="text-muted">{{ $payroll->employee->position->title }}</small>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-5 text-muted small">NIK</div>
                    <div class="col-7 small">{{ $payroll->employee->nik }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted small">Departemen</div>
                    <div class="col-7 small">{{ $payroll->employee->department->name }}</div>
                </div>
                <div class="row">
                    <div class="col-5 text-muted small">Periode</div>
                    <div class="col-7 small">{{ $payroll->month }} {{ $payroll->year }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-exclamation-triangle me-2"></i>Perhatian
                </h6>
            </div>
            <div class="card-body">
                <ul class="small mb-0 ps-3">
                    <li>Pastikan perhitungan sudah benar</li>
                    <li>Gaji bersih dihitung otomatis</li>
                    <li>Perubahan akan tersimpan permanent</li>
                    <li>Payroll yang sudah dibayar tidak bisa diedit</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calculateNetSalary() {
        const basic = parseFloat(document.getElementById('basicSalary').value) || 0;
        const allowance = parseFloat(document.getElementById('totalAllowance').value) || 0;
        const deduction = parseFloat(document.getElementById('totalDeduction').value) || 0;
        
        const net = (basic + allowance) - deduction;
        
        document.getElementById('netSalary').textContent = 'Rp ' + net.toLocaleString('id-ID');
    }

    document.getElementById('basicSalary').addEventListener('input', calculateNetSalary);
    document.getElementById('totalAllowance').addEventListener('input', calculateNetSalary);
    document.getElementById('totalDeduction').addEventListener('input', calculateNetSalary);
</script>
@endpush