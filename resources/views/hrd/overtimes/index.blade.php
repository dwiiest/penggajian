@extends('layouts.app')

@section('title', 'Data Lembur')
@section('page-title', 'Data Lembur (Overtime)')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">Data Lembur Karyawan</h4>
                <p class="text-muted mb-0">Kelola data lembur dan approval</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('hrd.overtimes.report') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan
                </a>
                <a href="{{ route('hrd.overtimes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Lembur
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
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="stat-label">Total Lembur</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-warning">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $stats['pending'] }}</div>
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
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value">{{ $stats['approved'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="stat-card card-info">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <div class="stat-label">Total Jam</div>
                    <div class="stat-value">{{ number_format($stats['total_hours'], 1) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg">
        <div class="stat-card card-danger">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-label">Total Bayar</div>
                    <div class="stat-value" style="font-size: 1.2rem;">Rp {{ number_format($stats['total_pay'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-card">
            <form action="{{ route('hrd.overtimes.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" class="form-control" name="date" 
                           value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Overtime::getStatuses() as $key => $label)
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
                            <th width="18%">Karyawan</th>
                            <th width="10%">Tanggal</th>
                            <th width="10%">Jam Mulai</th>
                            <th width="10%">Jam Selesai</th>
                            <th width="8%">Total Jam</th>
                            <th width="10%">Tarif/Jam</th>
                            <th width="12%">Total Bayar</th>
                            <th width="8%">Status</th>
                            <th width="9%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $index => $overtime)
                        <tr>
                            <td>{{ $overtimes->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $overtime->employee->user->name }}</span>
                                        <small class="text-muted">{{ $overtime->employee->position->title }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $overtime->date->format('d M Y') }}</span>
                                <br>
                                <small class="text-muted">{{ $overtime->date->format('l') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($overtime->total_hours, 1) }} jam</span>
                            </td>
                            <td>
                                <small>Rp {{ number_format($overtime->hourly_rate, 0, ',', '.') }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $overtime->status_badge }}">
                                    {{ $overtime->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @if($overtime->status === 'pending')
                                        <form action="{{ route('hrd.overtimes.approve', $overtime) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="Setujui"
                                                    onclick="return confirm('Setujui lembur ini?')">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('hrd.overtimes.reject', $overtime) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Tolak"
                                                    onclick="return confirm('Tolak lembur ini?')">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('hrd.overtimes.edit', $overtime) }}" 
                                           class="btn btn-sm btn-light" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('hrd.overtimes.show', $overtime) }}" 
                                           class="btn btn-sm btn-light" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada data lembur</p>
                                <a href="{{ route('hrd.overtimes.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Lembur
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($overtimes->hasPages())
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small">
                        Menampilkan {{ $overtimes->firstItem() }} - {{ $overtimes->lastItem() }} 
                        dari {{ $overtimes->total() }} data
                    </div>
                    {{ $overtimes->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection