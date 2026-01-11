@extends('layouts.app')

@section('title', 'Detail Department')
@section('page-title', 'Detail Department')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-1">{{ $department->name }}</h5>
                    <small class="text-muted">Dibuat: {{ $department->created_at->format('d M Y') }}</small>
                </div>
                <div>
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary">Edit</a>
                </div>
            </div>

            <div class="mb-3">
                <h6 class="fw-semibold">Deskripsi</h6>
                <p class="text-muted">{{ $department->description ?? '-' }}</p>
            </div>

            <div>
                <h6 class="fw-semibold">Status</h6>
                <span class="badge bg-{{ $department->status_badge }}">{{ $department->status_label }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
