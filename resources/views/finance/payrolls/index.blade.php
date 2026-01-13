@extends('layouts.app')

@section('title', 'Kelola Penggajian')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Kelola Penggajian</h2>
                <p class="text-muted mb-0">Periode {{ $month }} {{ $year }}</p>
            </div>
            <div>
                <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Generate Payroll
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Gaji</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($summary['total_salary'], 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="bi bi-cash-stack text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Tunjangan</p>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($summary['total_allowance'], 0, ',', '.') }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 rounded">
                        <i class="bi bi-plus-circle text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Sudah Dibayar</p>
                        <h4 class="fw-bold mb-0">{{ $summary['paid'] }}/{{ $summary['total'] }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="bi bi-check-circle text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Draft</p>
                        <h4 class="fw-bold mb-0">{{ $summary['draft'] }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 rounded">
                        <i class="bi bi-hourglass-split text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Actions -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('finance.payrolls.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">Bulan</label>
                <select name="month" class="form-select">
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small">Tahun</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Dibayar</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('finance.payrolls.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </a>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('finance.payrolls.export', ['month' => $month, 'year' => $year, 'status' => $status]) }}" 
                   class="btn btn-success w-100">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card border-0 shadow-sm mb-3" id="bulkActionsCard" style="display: none;">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-semibold"><span id="selectedCount">0</span> item dipilih</span>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkPay()">
                    <i class="bi bi-check-circle me-1"></i>Bayar Terpilih
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payroll Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th class="border-0">Karyawan</th>
                        <th class="border-0">NIK/NIP</th>
                        <th class="border-0">Jabatan</th>
                        <th class="border-0">Gaji Pokok</th>
                        <th class="border-0">Tunjangan</th>
                        <th class="border-0">Potongan</th>
                        <th class="border-0">Gaji Bersih</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td>
                            @if($payroll->status !== 'paid')
                            <input type="checkbox" class="form-check-input payroll-checkbox" 
                                   value="{{ $payroll->id }}">
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 35px; height: 35px; font-size: 14px;">
                                    {{ substr($payroll->employee->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $payroll->employee->user->name }}</div>
                                    <small class="text-muted">{{ $payroll->employee->department->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $payroll->employee->nik }}</div>
                            <small class="text-muted">{{ $payroll->employee->nip }}</small>
                        </td>
                        <td>{{ $payroll->employee->position->title }}</td>
                        <td>Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        <td class="text-success">Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                        <td class="text-danger">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                        <td class="fw-bold">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                        <td>
                            @if($payroll->status === 'paid')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Dibayar
                                </span>
                                <br><small class="text-muted">{{ $payroll->payment_date->format('d/m/Y') }}</small>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i>Draft
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('finance.payrolls.show', $payroll->id) }}" 
                                   class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($payroll->status !== 'paid')
                                <a href="{{ route('finance.payrolls.edit', $payroll->id) }}" 
                                   class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-success" 
                                        onclick="payPayroll({{ $payroll->id }})" title="Bayar">
                                    <i class="bi bi-cash"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deletePayroll({{ $payroll->id }})" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @else
                                <a href="{{ route('finance.payrolls.download-payslip', $payroll->id) }}" 
                                   class="btn btn-outline-info" title="Download Slip">
                                    <i class="bi bi-download"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">Tidak ada data payroll untuk periode ini</p>
                            <a href="{{ route('finance.payrolls.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Generate Payroll
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payrolls->hasPages())
    <div class="card-footer bg-white">
        {{ $payrolls->links() }}
    </div>
    @endif
</div>

<form id="bulkPayForm" method="POST" action="{{ route('finance.payrolls.bulk-pay') }}" style="display: none;">
    @csrf
    <div id="bulkPayrollIds"></div>
</form>

<form id="payForm" method="POST" style="display: none;">
    @csrf
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payroll-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    document.querySelectorAll('.payroll-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = document.querySelectorAll('.payroll-checkbox:checked');
        const bulkCard = document.getElementById('bulkActionsCard');
        const countSpan = document.getElementById('selectedCount');
        
        if (selected.length > 0) {
            bulkCard.style.display = 'block';
            countSpan.textContent = selected.length;
        } else {
            bulkCard.style.display = 'none';
        }
    }

    function bulkPay() {
        const selected = document.querySelectorAll('.payroll-checkbox:checked');
        if (selected.length === 0) {
            alert('Pilih payroll yang akan dibayar');
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin membayar ${selected.length} payroll?`)) {
            return;
        }

        const form = document.getElementById('bulkPayForm');
        const container = document.getElementById('bulkPayrollIds');
        container.innerHTML = '';

        selected.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payroll_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        form.submit();
    }

    function payPayroll(id) {
        if (!confirm('Apakah Anda yakin ingin membayar payroll ini?')) {
            return;
        }

        const form = document.getElementById('payForm');
        form.action = `/finance/payrolls/${id}/pay`;
        form.submit();
    }

    function deletePayroll(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus payroll ini?')) {
            return;
        }

        const form = document.getElementById('deleteForm');
        form.action = `/finance/payrolls/${id}`;
        form.submit();
    }
</script>
@endpush