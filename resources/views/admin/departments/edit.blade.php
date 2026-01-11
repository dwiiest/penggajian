@extends('layouts.app')

@section('title', 'Edit Department')
@section('page-title', 'Edit Department')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('departments.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit Department: {{ $department->name }}</h4>
                <p class="text-muted mb-0">Perbarui informasi department</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-building me-2"></i>Informasi Department
                </h6>

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Nama <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $department->name) }}"
                           placeholder="Contoh: IT, HR, Finance"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" {{ old('status', $department->status) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $department->status) == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" id="description" placeholder="Deskripsikan department (opsional)" class="form-control" rows="4">{{ old('description', $department->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('departments.index') }}" class="btn btn-light">
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
                Informasi Department
            </h6>

            <div class="mb-3">
                <small class="text-muted d-block">Department ID</small>
                <span class="fw-semibold">#{{ $department->id }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $department->created_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Terakhir Diupdate</small>
                <span class="fw-semibold">{{ $department->updated_at->format('d F Y H:i') }}</span>
            </div>

            <hr>

            <div class="d-grid gap-2">
                <a href="{{ route('departments.show', $department) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-2"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
