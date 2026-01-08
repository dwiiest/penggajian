@extends('layouts.app')

@section('title', 'Kelola Role')
@section('page-title', 'Manajemen Role')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Daftar Role</h4>
                <p class="text-muted mb-0">Kelola role pengguna sistem</p>
            </div>
            <a href="{{ route('admin.user-roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Role
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama Role</th>
                            <th width="35%">Deskripsi</th>
                            <th width="10%" class="text-center">Jumlah User</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Dibuat</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-shield-check text-primary"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td>{{ $role->description ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $role->users_count }} User</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.user-roles.toggle-status', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="badge bg-{{ $role->status_badge }} border-0" 
                                            onclick="return confirm('Yakin ingin mengubah status role ini?')">
                                        {{ $role->status_label }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ $role->created_at->format('d M Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.user-roles.show', $role) }}" 
                                       class="btn btn-sm btn-light" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.user-roles.edit', $role) }}" 
                                       class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.user-roles.destroy', $role) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus role ini?')">
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
                                <p class="text-muted mt-2">Belum ada data role</p>
                                <a href="{{ route('admin.user-roles.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Role
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection