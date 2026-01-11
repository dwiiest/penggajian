@extends('layouts.app')

@section('title', 'Tambah Lembur')
@section('page-title', 'Tambah Data Lembur')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('hrd.overtimes.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Tambah Data Lembur</h4>
                <p class="text-muted mb-0">Isi form di bawah untuk menambah data lembur</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('hrd.overtimes.store') }}" method="POST" id="overtimeForm">
                @csrf
                
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-clock-history me-2"></i>Data Lembur
                </h6>

                <div class="mb-4">
                    <label for="employee_id" class="form-label fw-semibold">
                        Karyawan <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                            id="employee_id" 
                            name="employee_id" 
                            required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->name }} - {{ $employee->nik }} ({{ $employee->department->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="date" class="form-label fw-semibold">
                        Tanggal Lembur <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('date') is-invalid @enderror" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', today()->format('Y-m-d')) }}"
                           required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="start_time" class="form-label fw-semibold">
                            Jam Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time', '17:00') }}"
                               required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Waktu mulai lembur</small>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="end_time" class="form-label fw-semibold">
                            Jam Selesai <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time', '20:00') }}"
                               required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Waktu selesai lembur</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="hourly_rate" class="form-label fw-semibold">
                        Tarif per Jam <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" 
                               class="form-control @error('hourly_rate') is-invalid @enderror" 
                               id="hourly_rate" 
                               name="hourly_rate" 
                               value="{{ old('hourly_rate', '50000') }}"
                               min="0"
                               step="1000"
                               required>
                    </div>
                    @error('hourly_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Tarif pembayaran per jam lembur</small>
                </div>

                <!-- Auto Calculate Display -->
                <div class="alert alert-info">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-calculator me-2"></i>Perhitungan Otomatis
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Total Jam Lembur:</small>
                            <div class="fw-bold fs-5" id="total_hours_display">-</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Total Pembayaran:</small>
                            <div class="fw-bold fs-5 text-success" id="total_pay_display">Rp 0</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">
                        Keterangan
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Deskripsi pekerjaan lembur...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('hrd.overtimes.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Lembur
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi Lembur
            </h6>
            <ul class="list-unstyled small">
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Lembur akan masuk status <strong>Pending</strong>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    HRD perlu approval untuk pembayaran
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Total jam dihitung otomatis
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Tarif bisa disesuaikan per karyawan
                </li>
            </ul>
        </div>

        <div class="chart-card mt-3">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightbulb text-warning me-2"></i>
                Tips Pengisian
            </h6>
            <ul class="list-unstyled small">
                <li class="mb-2">
                    <strong>Jam Lembur:</strong> Biasanya setelah jam kerja normal (setelah 17:00)
                </li>
                <li class="mb-2">
                    <strong>Keterangan:</strong> Jelaskan pekerjaan yang dilakukan
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const hourlyRate = document.getElementById('hourly_rate');
    const totalHoursDisplay = document.getElementById('total_hours_display');
    const totalPayDisplay = document.getElementById('total_pay_display');

    function calculateTotal() {
        if (startTime.value && endTime.value && hourlyRate.value) {
            const start = new Date('2000-01-01 ' + startTime.value);
            let end = new Date('2000-01-01 ' + endTime.value);

            if (end <= start) {
                end = new Date(end.getTime() + 24 * 60 * 60 * 1000);
            }

            const diffMinutes = (end - start) / (1000 * 60);
            const hours = diffMinutes / 60;

            const rate = parseFloat(hourlyRate.value) || 0;
            const totalPay = hours * rate;

            totalHoursDisplay.textContent = hours.toFixed(2) + ' jam';
            totalPayDisplay.textContent = 'Rp ' + totalPay.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        }
    }

    startTime.addEventListener('change', calculateTotal);
    endTime.addEventListener('change', calculateTotal);
    hourlyRate.addEventListener('input', calculateTotal);

    calculateTotal();
});
</script>
@endpush