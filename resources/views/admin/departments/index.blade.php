@extends('layouts.app')

@section('title', 'Kelola Department')
@section('page-title', 'Manajemen Department')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Daftar Department</h4>
                <p class="text-muted mb-0">Kelola department perusahaan</p>
            </div>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Department
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('departments.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cari Department</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nama atau deskripsi...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>
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
                            <th width="30%">Nama</th>
                            <th width="35%">Deskripsi</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $index => $department)
                        <tr>
                            <td>{{ $departments->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-semibold d-block">{{ $department->name }}</span>
                            </td>
                            <td>{{ Str::limit($department->description, 100) }}</td>
                            <td class="text-center">
                                <form action="{{ route('departments.toggle-status', $department) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            class="badge bg-{{ $department->status_badge }} border-0"
                                            onclick="return confirm('Yakin ingin mengubah status department ini?')">
                                        {{ $department->status_label }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('departments.show', $department) }}" 
                                       class="btn btn-sm btn-light" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('departments.edit', $department) }}" 
                                       class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('departments.destroy', $department) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus department ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data department</p>
                                <a href="{{ route('departments.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Department
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($departments->hasPages())
            <div class="p-3 border-top">
                {{ $departments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
