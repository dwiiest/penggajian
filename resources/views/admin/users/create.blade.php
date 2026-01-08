@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Tambah User Baru</h4>
                <p class="text-muted mb-0">Isi form di bawah untuk menambah user baru</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-person-circle me-2"></i>Informasi Dasar
                </h6>

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="user@example.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_role_id" class="form-label fw-semibold">
                        Role <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('user_role_id') is-invalid @enderror" 
                            id="user_role_id" 
                            name="user_role_id" 
                            required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('user_role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                                @if($role->description)
                                    - {{ $role->description }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('user_role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-shield-lock me-2"></i>Keamanan
                </h6>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        Password <span class="text-danger">*</span>
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Password minimal 8 karakter</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        Konfirmasi Password <span class="text-danger">*</span>
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           placeholder="Ulangi password"
                           required>
                </div>

                <hr class="my-4">

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
                               {{ old('status', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_active">
                            <span class="badge bg-success">Aktif</span>
                            <small class="text-muted ms-2">User dapat login</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('status') is-invalid @enderror" 
                               type="radio" 
                               name="status" 
                               id="status_inactive" 
                               value="0" 
                               {{ old('status') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_inactive">
                            <span class="badge bg-secondary">Nonaktif</span>
                            <small class="text-muted ms-2">User tidak dapat login</small>
                        </label>
                    </div>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan User
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
                    <small>Email harus unik dan valid</small>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Password minimal 8 karakter</small>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Pilih role sesuai jabatan</small>
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <small>Status aktif agar dapat login</small>
                </li>
            </ul>

            <div class="alert alert-info mt-3">
                <small>
                    <i class="bi bi-lightbulb me-2"></i>
                    Untuk data karyawan lengkap, tambahkan di menu "Data Karyawan"
                </small>
            </div>
        </div>
    </div>
</div>
@endsection