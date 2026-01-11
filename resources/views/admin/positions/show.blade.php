@extends('layouts.app')

@section('title', 'Detail Jabatan')
@section('page-title', 'Detail Jabatan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-1">{{ $position->title }}</h5>
                    <small class="text-muted">Dibuat: {{ $position->created_at->format('d M Y') }}</small>
                </div>
                <div>
                    <a href="{{ route('positions.edit', $position) }}" class="btn btn-sm btn-primary">Edit</a>
                </div>
            </div>

            <div class="mb-3">
                <h6 class="fw-semibold">Gaji Pokok</h6>
                <p class="text-muted">Rp {{ $position->base_salary_formatted }}</p>
            </div>

            <div class="mb-3">
                <h6 class="fw-semibold">Tunjangan Transport</h6>
                <p class="text-muted">Rp {{ $position->transport_allowance_formatted }}</p>
            </div>

            <div class="mb-3">
                <h6 class="fw-semibold">Tunjangan Makan</h6>
                <p class="text-muted">Rp {{ $position->meal_allowance_formatted }}</p>
            </div>

            <div>
                <h6 class="fw-semibold">Status</h6>
                <span class="badge bg-{{ $position->status_badge }}">{{ $position->status_label }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')