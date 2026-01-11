@extends('layouts.app')

@section('title', 'Tambah Jabatan')
@section('page-title', 'Tambah Jabatan Baru')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('positions.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Tambah Jabatan Baru</h4>
                <p class="text-muted mb-0">Isi form di bawah untuk menambah jabatan dan pengaturan gaji</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-briefcase me-2"></i>Detail Jabatan
                </h6>

                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Senior Developer, Manager" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="base_salary" class="form-label fw-semibold">Gaji Pokok <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="base_salary" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary') }}" placeholder="1000000" required>
                        @error('base_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="transport_allowance" class="form-label fw-semibold">Tunjangan Transport</label>
                        <input type="number" step="0.01" id="transport_allowance" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', 0) }}" placeholder="0">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="meal_allowance" class="form-label fw-semibold">Tunjangan Makan</label>
                    <input type="number" step="0.01" id="meal_allowance" name="meal_allowance" class="form-control" value="{{ old('meal_allowance', 0) }}" placeholder="0">
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('positions.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Jabatan
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
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i><small>Pastikan gaji pokok dalam format angka tanpa pemisah</small></li>
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i><small>Tunjangan bisa diset ke 0 jika tidak ada</small></li>
                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i><small>Status nonaktif akan menyembunyikan jabatan dari pemilihan</small></li>
            </ul>
            <div class="alert alert-info mt-3">
                <small><i class="bi bi-lightbulb me-2"></i>Gunakan angka bulat untuk memudahkan perhitungan payroll</small>
            </div>
        </div>
    </div>
</div>
@endsection
