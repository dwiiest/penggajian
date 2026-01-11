@extends('layouts.app')

@section('title', 'Edit Jabatan')
@section('page-title', 'Edit Jabatan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('positions.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit Jabatan: {{ $position->title }}</h4>
                <p class="text-muted mb-0">Perbarui informasi jabatan dan gaji</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('positions.update', $position) }}" method="POST">
                @csrf
                @method('PUT')

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-award me-2"></i>Detail Jabatan
                </h6>

                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $position->title) }}" placeholder="Contoh: Senior Developer, Manager" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="base_salary" class="form-label fw-semibold">Gaji Pokok <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="base_salary" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $position->base_salary) }}" placeholder="1000000" required>
                        @error('base_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="transport_allowance" class="form-label fw-semibold">Tunjangan Transport</label>
                        <input type="number" step="0.01" id="transport_allowance" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', $position->transport_allowance) }}" placeholder="0">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="meal_allowance" class="form-label fw-semibold">Tunjangan Makan</label>
                    <input type="number" step="0.01" id="meal_allowance" name="meal_allowance" class="form-control" value="{{ old('meal_allowance', $position->meal_allowance) }}" placeholder="0">
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" {{ old('status', $position->status) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $position->status) == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('positions.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi Jabatan
            </h6>

            <div class="mb-3">
                <small class="text-muted d-block">Jabatan ID</small>
                <span class="fw-semibold">#{{ $position->id }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Gaji Pokok</small>
                <span class="fw-semibold">Rp {{ $position->base_salary_formatted }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $position->created_at->format('d F Y H:i') }}</span>
            </div>

            <hr>

            <div class="d-grid gap-2">
                <a href="{{ route('positions.show', $position) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-2"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
