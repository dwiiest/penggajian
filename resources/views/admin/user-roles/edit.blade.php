@extends('layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.user-roles.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit Role: {{ $userRole->name }}</h4>
                <p class="text-muted mb-0">Perbarui informasi role</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('admin.user-roles.update', $userRole) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Nama Role <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $userRole->name) }}"
                           placeholder="Contoh: admin, hrd, finance"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Gunakan huruf kecil tanpa spasi</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-semibold">
                        Deskripsi
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Deskripsi role...">{{ old('description', $userRole->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Status <span class="text-danger">*</span>
                    </label>
                    <div class="form-check">
                        <input class="form-check-input @error('status') is-invalid @enderror" 
                               type="radio" 
                               name="status" 
                               id="status_active" 
                               value="1" 
                               {{ old('status', $userRole->status) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_active">
                            <span class="badge bg-success">Aktif</span>
                            <small class="text-muted ms-2">Role dapat digunakan</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('status') is-invalid @enderror" 
                               type="radio" 
                               name="status" 
                               id="status_inactive" 
                               value="0" 
                               {{ old('status', $userRole->status) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_inactive">
                            <span class="badge bg-secondary">Nonaktif</span>
                            <small class="text-muted ms-2">Role tidak dapat digunakan</small>
                        </label>
                    </div>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.user-roles.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi Role
            </h6>
            
            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $userRole->created_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Terakhir Diupdate</small>
                <span class="fw-semibold">{{ $userRole->updated_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Jumlah User</small>
                <span class="badge bg-info">{{ $userRole->users()->count() }} User</span>
            </div>

            <hr>

            <div class="alert alert-warning">
                <small>
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Role dengan user tidak dapat dihapus
                </small>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.user-roles.show', $userRole) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-2"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection