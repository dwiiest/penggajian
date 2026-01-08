@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit User: {{ $user->name }}</h4>
                <p class="text-muted mb-0">Perbarui informasi user</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
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
                            <option value="{{ $role->id }}" 
                                {{ old('user_role_id', $user->user_role_id) == $role->id ? 'selected' : '' }}>
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
                    <i class="bi bi-shield-lock me-2"></i>Ubah Password (Opsional)
                </h6>

                <div class="alert alert-info">
                    <small>
                        <i class="bi bi-info-circle me-2"></i>
                        Kosongkan jika tidak ingin mengubah password
                    </small>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        Password Baru
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Password minimal 8 karakter</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           placeholder="Ulangi password baru">
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
                               {{ old('status', $user->status) == '1' ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
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
                               {{ old('status', $user->status) == '0' ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label" for="status_inactive">
                            <span class="badge bg-secondary">Nonaktif</span>
                            <small class="text-muted ms-2">User tidak dapat login</small>
                        </label>
                    </div>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    
                    @if($user->id === auth()->id())
                        <small class="text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Anda tidak dapat mengubah status akun sendiri
                        </small>
                    @endif
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi User
            </h6>
            
            <div class="mb-3">
                <small class="text-muted d-block">User ID</small>
                <span class="fw-semibold">#{{ $user->id }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Status Karyawan</small>
                @if($user->employee)
                    <span class="badge bg-success">Terdaftar sebagai Karyawan</span>
                    <br><small class="text-muted">NIK: {{ $user->employee->nik }}</small>
                @else
                    <span class="badge bg-secondary">Bukan Karyawan</span>
                @endif
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $user->created_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Terakhir Diupdate</small>
                <span class="fw-semibold">{{ $user->updated_at->format('d F Y H:i') }}</span>
            </div>

            <hr>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-2"></i>Lihat Detail
                </a>
                
                <a href="{{ route('admin.users.reset-password', $user) }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-key me-2"></i>Reset Password
                </a>
            </div>
        </div>

        @if($user->id === auth()->id())
        <div class="alert alert-warning mt-3">
            <small>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Ini adalah akun Anda sendiri. Berhati-hatilah saat mengubah data.
            </small>
        </div>
        @endif
    </div>
</div>
@endsection