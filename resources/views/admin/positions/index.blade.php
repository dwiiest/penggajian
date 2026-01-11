@extends('layouts.app')

@section('title', 'Kelola Jabatan')
@section('page-title', 'Manajemen Jabatan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Daftar Jabatan</h4>
                <p class="text-muted mb-0">Kelola jabatan dan gaji dasar</p>
            </div>
            <a href="{{ route('positions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Jabatan
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('positions.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Cari Jabatan</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Judul jabatan...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter Status</label>
                    <select name="status" class="form-select">
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
                            <th width="25%">Jabatan</th>
                            <th width="15%">Gaji Pokok</th>
                            <th width="15%">Tunj. Transport</th>
                            <th width="15%">Tunj. Makan</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $index => $position)
                        <tr>
                            <td>{{ $positions->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $position->title }}</td>
                            <td>Rp {{ $position->base_salary_formatted }}</td>
                            <td>Rp {{ $position->transport_allowance_formatted }}</td>
                            <td>Rp {{ $position->meal_allowance_formatted }}</td>
                            <td class="text-center">
                                <form action="{{ route('positions.toggle-status', $position) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="badge bg-{{ $position->status_badge }} border-0" onclick="return confirm('Yakin ingin mengubah status jabatan ini?')">
                                        {{ $position->status_label }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('positions.show', $position) }}" class="btn btn-sm btn-light" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('positions.edit', $position) }}" class="btn btn-sm btn-light" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('positions.destroy', $position) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus jabatan ini?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data jabatan</p>
                                <a href="{{ route('positions.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Jabatan</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($positions->hasPages())
            <div class="p-3 border-top">
                {{ $positions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
