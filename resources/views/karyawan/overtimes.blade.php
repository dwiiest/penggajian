@extends('layouts.app')

@section('title', 'Daftar Lembur')
@section('page-title', 'Daftar Lembur Saya')

@section('content')
<!-- Filter & Action Card -->
<div class="chart-card mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-9">
            <form action="{{ route('karyawan.overtimes') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('karyawan.overtimes') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
        <div class="col-md-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#overtimeModal">
                <i class="bi bi-plus-circle me-1"></i> Ajukan Lembur
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card-primary">
            <div class="icon-box">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Total Lembur</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card-warning">
            <div class="icon-box">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card-success">
            <div class="icon-box">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Disetujui</div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card card-info">
            <div class="icon-box">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-label">Total Upah</div>
            <div class="stat-value">Rp {{ number_format($stats['total_pay'], 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<!-- Overtime Table -->
<div class="table-card">
    <div class="card-header">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-list-check text-primary me-2"></i>
            Daftar Lembur {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM Y') }}
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Total Jam</th>
                    <th>Tarif/Jam</th>
                    <th>Total Upah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overtimes as $index => $overtime)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($overtime->date)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-primary">{{ substr($overtime->start_time, 0, 5) }}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ substr($overtime->end_time, 0, 5) }}</span>
                    </td>
                    <td>{{ $overtime->total_hours }} jam</td>
                    <td>Rp {{ number_format($overtime->hourly_rate, 0, ',', '.') }}</td>
                    <td class="fw-bold">Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}</td>
                    <td>
                        @if($overtime->status === 'pending')
                            <span class="badge bg-warning">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu
                            </span>
                        @elseif($overtime->status === 'approved')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i> Disetujui
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i> Ditolak
                            </span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" 
                                data-bs-toggle="modal" 
                                data-bs-target="#detailModal{{ $overtime->id }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- Detail Modal -->
                <div class="modal fade" id="detailModal{{ $overtime->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-info-circle text-primary me-2"></i>
                                    Detail Lembur
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="text-muted small">Tanggal</label>
                                        <p class="fw-semibold">{{ \Carbon\Carbon::parse($overtime->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small">Jam Mulai</label>
                                        <p class="fw-semibold">{{ substr($overtime->start_time, 0, 5) }} WIB</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small">Jam Selesai</label>
                                        <p class="fw-semibold">{{ substr($overtime->end_time, 0, 5) }} WIB</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small">Total Jam</label>
                                        <p class="fw-semibold">{{ $overtime->total_hours }} Jam</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small">Tarif per Jam</label>
                                        <p class="fw-semibold">Rp {{ number_format($overtime->hourly_rate, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted small">Total Upah</label>
                                        <p class="fw-semibold text-success fs-5">Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted small">Deskripsi Pekerjaan</label>
                                        <p class="fw-semibold">{{ $overtime->description }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted small">Status</label>
                                        <p>
                                            @if($overtime->status === 'pending')
                                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                                            @elseif($overtime->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @if($overtime->approver)
                                                    <br><small class="text-muted">oleh {{ $overtime->approver->name }}</small>
                                                    <br><small class="text-muted">pada {{ \Carbon\Carbon::parse($overtime->approved_at)->format('d/m/Y H:i') }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                                @if($overtime->approver)
                                                    <br><small class="text-muted">oleh {{ $overtime->approver->name }}</small>
                                                    <br><small class="text-muted">pada {{ \Carbon\Carbon::parse($overtime->approved_at)->format('d/m/Y H:i') }}</small>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada data lembur untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Overtime Modal -->
<div class="modal fade" id="overtimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle text-success me-2"></i>
                    Ajukan Lembur Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('karyawan.overtimes.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required max="{{ date('Y-m-d') }}">
                        <small class="text-muted">Pilih tanggal ketika lembur dilakukan</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Jelaskan pekerjaan lembur yang dilakukan secara detail..."></textarea>
                        <small class="text-muted">Minimal 20 karakter</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Pengajuan lembur akan direview oleh HRD. Pastikan data yang diisi sudah benar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send me-1"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection