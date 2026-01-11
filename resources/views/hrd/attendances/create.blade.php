@extends('layouts.app')

@section('title', 'Tambah Absensi')
@section('page-title', 'Tambah Absensi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('hrd.attendances.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Tambah Data Absensi</h4>
                <p class="text-muted mb-0">Isi form di bawah untuk menambah absensi</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('hrd.attendances.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Data Absensi
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
                        Tanggal <span class="text-danger">*</span>
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
                        <label for="time_in" class="form-label fw-semibold">
                            Jam Masuk
                        </label>
                        <input type="time" 
                               class="form-control @error('time_in') is-invalid @enderror" 
                               id="time_in" 
                               name="time_in" 
                               value="{{ old('time_in') }}">
                        @error('time_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="time_out" class="form-label fw-semibold">
                            Jam Keluar
                        </label>
                        <input type="time" 
                               class="form-control @error('time_out') is-invalid @enderror" 
                               id="time_out" 
                               name="time_out" 
                               value="{{ old('time_out') }}">
                        @error('time_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="">-- Pilih Status --</option>
                        @foreach(\App\Models\Attendance::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="note" class="form-label fw-semibold">
                        Keterangan
                    </label>
                    <textarea class="form-control @error('note') is-invalid @enderror" 
                              id="note" 
                              name="note" 
                              rows="3"
                              placeholder="Keterangan tambahan (opsional)...">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('hrd.attendances.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Status Absensi
            </h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <span class="badge bg-success">Hadir</span>
                    <small class="d-block text-muted mt-1">Karyawan hadir tepat waktu</small>
                </li>
                <li class="mb-2">
                    <span class="badge bg-warning">Terlambat</span>
                    <small class="d-block text-muted mt-1">Karyawan hadir terlambat</small>
                </li>
                <li class="mb-2">
                    <span class="badge bg-info">Izin</span>
                    <small class="d-block text-muted mt-1">Karyawan izin dengan surat</small>
                </li>
                <li class="mb-2">
                    <span class="badge bg-primary">Sakit</span>
                    <small class="d-block text-muted mt-1">Karyawan sakit dengan surat</small>
                </li>
                <li class="mb-2">
                    <span class="badge bg-danger">Alpha</span>
                    <small class="d-block text-muted mt-1">Tidak hadir tanpa keterangan</small>
                </li>
                <li class="mb-2">
                    <span class="badge bg-secondary">Cuti</span>
                    <small class="d-block text-muted mt-1">Karyawan sedang cuti</small>
                </li>
            </ul>
        </div>

        <div class="alert alert-info mt-3">
            <small>
                <i class="bi bi-lightbulb me-2"></i>
                Jam masuk dan keluar bersifat opsional tergantung status absensi
            </small>
        </div>

        <div class="alert alert-warning mt-3">
            <small>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Satu karyawan hanya bisa memiliki satu data absensi per hari
            </small>
        </div>
    </div>
</div>
@endsection