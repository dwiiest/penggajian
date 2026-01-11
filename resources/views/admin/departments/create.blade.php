@extends('layouts.app')

@section('title', 'Tambah Department')
@section('page-title', 'Tambah Department')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('departments.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Tambah Department</h4>
                <p class="text-muted mb-0">Isi form di bawah untuk menambah department baru</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

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
                           value="{{ old('name') }}"
                           placeholder="Contoh: IT, HR, Finance"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" id="description" placeholder="Deskripsikan department (opsional)" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('departments.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card bg-light">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Panduan
            </h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Nama department harus unik dan deskriptif</small>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Jika nonaktif, karyawan tidak dapat dipetakan ke department</small>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Deskripsi bersifat opsional namun membantu</small>
                </li>
            </ul>

            <div class="alert alert-info mt-3">
                <small>
                    <i class="bi bi-lightbulb me-2"></i>
                    Gunakan nama singkat seperti "IT" atau "Finance" untuk kemudahan pencarian
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
