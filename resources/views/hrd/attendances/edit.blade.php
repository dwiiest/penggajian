@extends('layouts.app')

@section('title', 'Edit Absensi')
@section('page-title', 'Edit Absensi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('hrd.attendances.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit Data Absensi</h4>
                <p class="text-muted mb-0">{{ $attendance->employee->user->name }} - {{ $attendance->date->format('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('hrd.attendances.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Data Absensi
                </h6>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Karyawan</label>
                    <div class="form-control-plaintext">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">{{ $attendance->employee->user->name }}</span>
                                <small class="text-muted">
                                    {{ $attendance->employee->nik }} - {{ $attendance->employee->position->title }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="date" class="form-label fw-semibold">
                        Tanggal <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('date') is-invalid @enderror" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', $attendance->date->format('Y-m-d')) }}"
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
                               value="{{ old('time_in', $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '') }}">
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
                               value="{{ old('time_out', $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '') }}">
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
                            <option value="{{ $key }}" {{ old('status', $attendance->status) == $key ? 'selected' : '' }}>
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
                              placeholder="Keterangan tambahan (opsional)...">{{ old('note', $attendance->note) }}</textarea>
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
                        <i class="bi bi-save me-2"></i>Update Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi Absensi
            </h6>
            
            <div class="mb-3">
                <small class="text-muted d-block">Departemen</small>
                <span class="badge bg-success">{{ $attendance->employee->department->name }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $attendance->created_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Terakhir Diupdate</small>
                <span class="fw-semibold">{{ $attendance->updated_at->format('d F Y H:i') }}</span>
            </div>

            @if($attendance->work_hours)
            <div class="mb-3">
                <small class="text-muted d-block">Total Jam Kerja</small>
                <span class="fw-semibold">{{ $attendance->work_hours }}</span>
            </div>
            @endif
        </div>

        <div class="chart-card mt-3 bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightbulb text-warning me-2"></i>
                Tips
            </h6>
            <ul class="list-unstyled small">
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Pastikan tanggal sesuai
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Jam keluar harus setelah jam masuk
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Status sesuai kondisi sebenarnya
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection