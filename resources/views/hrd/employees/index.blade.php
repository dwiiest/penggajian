@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Data Karyawan</h4>
                <p class="text-muted mb-0">Kelola data karyawan perusahaan</p>
            </div>
            <a href="{{ route('hrd.employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Karyawan
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.employees.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cari Karyawan</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="NIK, NIP, atau Nama...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Departemen</label>
                    <select class="form-select" name="department">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Jabatan</label>
                    <select class="form-select" name="position">
                        <option value="">Semua Jabatan</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ request('position') == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card card-primary">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-label">Total Karyawan</div>
                    <div class="stat-value">{{ $employees->total() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-success">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <div class="stat-label">Departemen</div>
                    <div class="stat-value">{{ $departments->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-warning">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div>
                    <div class="stat-label">Jabatan</div>
                    <div class="stat-value">{{ $positions->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-info">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-file-earmark-person"></i>
                </div>
                <div>
                    <div class="stat-label">Halaman</div>
                    <div class="stat-value">{{ $employees->currentPage() }}/{{ $employees->lastPage() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Karyawan</th>
                            <th width="15%">NIK / NIP</th>
                            <th width="15%">Jabatan</th>
                            <th width="15%">Departemen</th>
                            <th width="15%">Kontak</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $index => $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $employee->user->name }}</span>
                                        <small class="text-muted">{{ $employee->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <small class="text-muted">NIK:</small>
                                    <span class="d-block fw-semibold">{{ $employee->nik }}</span>
                                </div>
                                <div>
                                    <small class="text-muted">NIP:</small>
                                    <span class="d-block fw-semibold">{{ $employee->nip }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $employee->position->title }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $employee->department->name }}</span>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <i class="bi bi-telephone text-muted me-1"></i>
                                    <small>{{ $employee->phone_number ?? '-' }}</small>
                                </div>
                                <div>
                                    <i class="bi bi-bank text-muted me-1"></i>
                                    <small>{{ $employee->bank_name ?? '-' }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hrd.employees.show', $employee) }}" 
                                       class="btn btn-sm btn-light" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('hrd.employees.edit', $employee) }}" 
                                       class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('hrd.employees.destroy', $employee) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus data karyawan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data karyawan</p>
                                <a href="{{ route('hrd.employees.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Karyawan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Menampilkan {{ $employees->firstItem() }} - {{ $employees->lastItem() }} 
                        dari {{ $employees->total() }} data
                    </div>
                    {{ $employees->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection