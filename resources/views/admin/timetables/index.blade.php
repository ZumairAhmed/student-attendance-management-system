@extends('layouts.app')
@section('title', 'Timetables')
@section('subtitle', 'Manage all batch timetables')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="text-muted mb-0">All semester timetables</h6>
    <a href="{{ route('admin.timetables.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-2"></i>Create Timetable
    </a>
</div>

<div class="row g-3">
    @forelse($timetables as $timetable)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $timetable->batch->name }}</h5>
                        <p class="text-muted mb-0" style="font-size:13px;">
                            {{ $timetable->semester }} — {{ $timetable->academic_year }}
                        </p>
                    </div>
                    @if($timetable->status === 'active')
                        <span class="badge badge-present px-2 py-1 rounded-pill">Active</span>
                    @else
                        <span class="badge badge-absent px-2 py-1 rounded-pill">Inactive</span>
                    @endif
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                            <div style="font-size:18px;font-weight:800;color:#1e293b;">
                                {{ count($timetable->days) }}
                            </div>
                            <div style="font-size:11px;color:#94a3b8;">Days</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:10px;text-align:center;">
                            <div style="font-size:18px;font-weight:800;color:#1e293b;">
                                {{ $timetable->slots->where('is_continuation', false)->count() }}
                            </div>
                            <div style="font-size:11px;color:#94a3b8;">Slots</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div style="font-size:12px;color:#64748b;margin-bottom:6px;">
                        <i class="fas fa-calendar me-1"></i>
                        Effective: {{ \Carbon\Carbon::parse($timetable->effective_date)->format('d M Y') }}
                    </div>
                    <div style="font-size:12px;color:#64748b;">
                        <i class="fas fa-calendar-week me-1"></i>
                        Days: {{ implode(', ', $timetable->days) }}
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.timetables.show', $timetable) }}"
                        class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-eye me-1"></i>View & Edit
                    </a>
                    <a href="{{ route('admin.timetables.print', $timetable) }}"
                        class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="fas fa-print me-1"></i>Print
                    </a>
                    <form action="{{ route('admin.timetables.destroy', $timetable) }}"
                        method="POST" onsubmit="return confirm('Delete this timetable?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center p-5">
            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold text-muted">No Timetables Yet</h5>
            <p class="text-muted mb-3">Create your first semester timetable</p>
            <a href="{{ route('admin.timetables.create') }}" class="btn btn-primary mx-auto" style="width:fit-content;">
                <i class="fas fa-plus me-2"></i>Create Timetable
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection