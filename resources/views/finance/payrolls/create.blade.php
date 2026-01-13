@extends('layouts.app')

@section('title', 'Generate Payroll')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Generate Payroll</h2>
                <p class="text-muted mb-0">Pilih karyawan untuk generate penggajian</p>
            </div>
            <a href="{{ route('finance.payrolls.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Pilih Karyawan</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('finance.payrolls.generate') }}" id="generateForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" required>
                                <option value="January" {{ $month == 'January' ? 'selected' : '' }}>Januari</option>
                                <option value="February" {{ $month == 'February' ? 'selected' : '' }}>Februari</option>
                                <option value="March" {{ $month == 'March' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ $month == 'April' ? 'selected' : '' }}>April</option>
                                <option value="May" {{ $month == 'May' ? 'selected' : '' }}>Mei</option>
                                <option value="June" {{ $month == 'June' ? 'selected' : '' }}>Juni</option>
                                <option value="July" {{ $month == 'July' ? 'selected' : '' }}>Juli</option>
                                <option value="August" {{ $month == 'August' ? 'selected' : '' }}>Agustus</option>
                                <option value="September" {{ $month == 'September' ? 'selected' : '' }}>September</option>
                                <option value="October" {{ $month == 'October' ? 'selected' : '' }}>Oktober</option>
                                <option value="November" {{ $month == 'November' ? 'selected' : '' }}>November</option>
                                <option value="December" {{ $month == 'December' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}" 
                                   min="2020" max="2030" required>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllEmployees">
                            <label class="form-check-label fw-semibold" for="selectAllEmployees">
                                Pilih Semua Karyawan
                            </label>
                        </div>
                    </div>

                    <div class="list-group" style="max-height: 500px; overflow-y: auto;">
                        @foreach($employees as $employee)
                            @php
                                $alreadyGenerated = in_array($employee->id, $existingPayrolls);
                            @endphp
                            <label class="list-group-item {{ $alreadyGenerated ? 'disabled bg-light' : '' }}">
                                <div class="d-flex align-items-start">
                                    <input class="form-check-input me-3 mt-1 employee-checkbox" 
                                           type="checkbox" 
                                           name="employee_ids[]" 
                                           value="{{ $employee->id }}"
                                           {{ $alreadyGenerated ? 'disabled' : '' }}>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $employee->user->name }}</h6>
                                                <div class="text-muted small">
                                                    <span class="me-3">
                                                        <i class="bi bi-person-badge"></i> {{ $employee->nik }}
                                                    </span>
                                                    <span class="me-3">
                                                        <i class="bi bi-briefcase"></i> {{ $employee->position->title }}
                                                    </span>
                                                    <span>
                                                        <i class="bi bi-building"></i> {{ $employee->department->name }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($alreadyGenerated)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Sudah Generate
                                                    </span>
                                                @else
                                                    <div class="fw-semibold">Rp {{ number_format($employee->position->base_salary, 0, ',', '.') }}</div>
                                                    <small class="text-muted">Gaji Pokok</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id="generateBtn" disabled>
                            <i class="bi bi-check-circle me-2"></i>Generate Payroll (<span id="selectedCount">0</span>)
                        </button>
                        <a href="{{ route('finance.payrolls.index') }}" class="btn btn-outline-secondary">
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
                    <i class="bi bi-info-circle me-2"></i>Informasi
                </h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>Generate Payroll</strong> akan menghitung gaji karyawan berdasarkan:
                </p>
                <ul class="small mb-0">
                    <li>Gaji pokok dari jabatan</li>
                    <li>Tunjangan transport (per hari hadir)</li>
                    <li>Tunjangan makan (per hari hadir)</li>
                    <li>Lembur yang disetujui</li>
                    <li>Potongan (jika ada)</li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark border-0 py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-exclamation-triangle me-2"></i>Perhatian
                </h6>
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li>Pastikan data absensi sudah lengkap</li>
                    <li>Pastikan lembur sudah disetujui</li>
                    <li>Karyawan yang sudah di-generate akan ditandai</li>
                    <li>Payroll bisa diedit sebelum dibayar</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAllEmployees').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.employee-checkbox:not([disabled])');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const selected = document.querySelectorAll('.employee-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = selected;
        document.getElementById('generateBtn').disabled = selected === 0;
    }

    document.getElementById('generateForm').addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.employee-checkbox:checked').length;
        if (selected === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 karyawan untuk generate payroll');
            return false;
        }

        if (!confirm(`Generate payroll untuk ${selected} karyawan?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush