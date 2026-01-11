@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center">
            <a href="{{ route('hrd.employees.index') }}" class="btn btn-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-1 fw-bold">Edit Karyawan: {{ $employee->user->name }}</h4>
                <p class="text-muted mb-0">Perbarui informasi karyawan</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chart-card">
            <form action="{{ route('hrd.employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-person-circle me-2"></i>Informasi User
                </h6>

                <div class="mb-4">
                    <label class="form-label fw-semibold">User</label>
                    <div class="form-control-plaintext">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block">{{ $employee->user->name }}</span>
                                <small class="text-muted">{{ $employee->user->email }} - {{ ucfirst($employee->user->role->name) }}</small>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">User tidak dapat diubah</small>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-card-text me-2"></i>Data Karyawan
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="nik" class="form-label fw-semibold">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nik') is-invalid @enderror" 
                               id="nik" 
                               name="nik" 
                               value="{{ old('nik', $employee->nik) }}"
                               placeholder="Nomor Induk Karyawan"
                               required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="nip" class="form-label fw-semibold">
                            NIP <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nip') is-invalid @enderror" 
                               id="nip" 
                               name="nip" 
                               value="{{ old('nip', $employee->nip) }}"
                               placeholder="Nomor Induk Pegawai"
                               required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="position_id" class="form-label fw-semibold">
                            Jabatan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('position_id') is-invalid @enderror" 
                                id="position_id" 
                                name="position_id" 
                                required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" 
                                    {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }} - {{ $position->formatted_base_salary }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="department_id" class="form-label fw-semibold">
                            Departemen <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('department_id') is-invalid @enderror" 
                                id="department_id" 
                                name="department_id" 
                                required>
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" 
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-telephone me-2"></i>Informasi Kontak
                </h6>

                <div class="mb-4">
                    <label for="phone_number" class="form-label fw-semibold">
                        Nomor Telepon
                    </label>
                    <input type="text" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ old('phone_number', $employee->phone_number) }}"
                           placeholder="08xxxxxxxxxx">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold">
                        Alamat
                    </label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="3"
                              placeholder="Alamat lengkap...">{{ old('address', $employee->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-bank me-2"></i>Informasi Bank
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="bank_name" class="form-label fw-semibold">
                            Nama Bank
                        </label>
                        <input type="text" 
                               class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" 
                               name="bank_name" 
                               value="{{ old('bank_name', $employee->bank_name) }}"
                               placeholder="Contoh: BCA, BNI, Mandiri">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="account_number" class="form-label fw-semibold">
                            Nomor Rekening
                        </label>
                        <input type="text" 
                               class="form-control @error('account_number') is-invalid @enderror" 
                               id="account_number" 
                               name="account_number" 
                               value="{{ old('account_number', $employee->account_number) }}"
                               placeholder="Nomor rekening bank">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route('hrd.employees.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informasi Karyawan
            </h6>
            
            <div class="mb-3">
                <small class="text-muted d-block">ID Karyawan</small>
                <span class="fw-semibold">#{{ $employee->id }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Jabatan Saat Ini</small>
                <span class="badge bg-primary">{{ $employee->position->title }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Departemen Saat Ini</small>
                <span class="badge bg-success">{{ $employee->department->name }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Dibuat</small>
                <span class="fw-semibold">{{ $employee->created_at->format('d F Y H:i') }}</span>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Terakhir Diupdate</small>
                <span class="fw-semibold">{{ $employee->updated_at->format('d F Y H:i') }}</span>
            </div>

            <hr>

            <div class="d-grid gap-2">
                <a href="{{ route('hrd.employees.show', $employee) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-2"></i>Lihat Detail Lengkap
                </a>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <small>
                <i class="bi bi-lightbulb me-2"></i>
                Perubahan jabatan akan mempengaruhi perhitungan gaji
            </small>
        </div>
    </div>
</div>
@endsection