@extends('layouts.app')

@section('title', 'Data Absensi')
@section('page-title', 'Data Absensi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Data Absensi</h4>
                <p class="text-muted mb-0">Kelola absensi karyawan</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('hrd.attendances.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Absensi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="stat-card card-primary">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-success">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Hadir</div>
                    <div class="stat-value">{{ $stats['hadir'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-warning">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <div class="stat-label">Terlambat</div>
                    <div class="stat-value">{{ $stats['terlambat'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-info">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-file-text"></i>
                </div>
                <div>
                    <div class="stat-label">Izin</div>
                    <div class="stat-value">{{ $stats['izin'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-danger">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Alpha</div>
                    <div class="stat-value">{{ $stats['alpha'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.attendances.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" class="form-control" name="date" 
                           value="{{ request('date', today()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Attendance::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Departemen</label>
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
                    <label class="form-label fw-semibold">Cari</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nama/NIK...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
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
                            <th width="20%">Karyawan</th>
                            <th width="10%">Tanggal</th>
                            <th width="10%">Jam Masuk</th>
                            <th width="10%">Jam Keluar</th>
                            <th width="10%">Total Jam</th>
                            <th width="10%">Status</th>
                            <th width="15%">Keterangan</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $attendances->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $attendance->employee->user->name }}</span>
                                        <small class="text-muted">
                                            {{ $attendance->employee->position->title }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $attendance->date->format('d M Y') }}</span>
                                <br>
                                <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                            </td>
                            <td>
                                @if($attendance->time_in)
                                    <span class="badge {{ $attendance->isLate() ? 'bg-warning' : 'bg-success' }}">
                                        {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->time_out)
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->work_hours)
                                    <small>{{ $attendance->work_hours }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $attendance->status_badge }}">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $attendance->note ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hrd.attendances.edit', $attendance) }}" 
                                       class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('hrd.attendances.destroy', $attendance) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus data absensi ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data absensi</p>
                                <a href="{{ route('hrd.attendances.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Absensi
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Menampilkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem() }} 
                        dari {{ $attendances->total() }} data
                    </div>
                    {{ $attendances->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection