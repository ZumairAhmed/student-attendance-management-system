@extends('layouts.app')
@section('title', 'Lecturer Dashboard')
@section('content')

<div class="mb-4">
    <h6 class="text-muted">Welcome back, <strong>{{ auth()->user()->name }}</strong> — Your assigned subjects</h6>
</div>

@if(count($subjectData) === 0)
    <div class="card text-center p-5">
        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
        <h5 class="fw-bold">No Subjects Assigned</h5>
        <p class="text-muted">Contact the administrator to get subjects assigned to you.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($subjectData as $data)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $data['subject']->name }}</h5>
                            <span class="badge bg-light text-dark me-2">{{ $data['subject']->code }}</span>
                            <span class="badge badge-present px-2 py-1 rounded-pill">{{ $data['subject']->batch->name }}</span>
                        </div>
                        <span class="badge bg-light text-muted">Sem {{ $data['subject']->semester }}</span>
                    </div>

                    <div class="d-flex gap-3 mb-3">
                        <div class="text-center">
                            <div style="font-size:24px;font-weight:800;color:#1e293b;">{{ $data['totalSessions'] }}</div>
                            <div style="font-size:12px;color:#94a3b8;">Sessions</div>
                        </div>
                        @if($data['subject']->is_locked)
                        <div class="ms-auto">
                            <span class="badge badge-absent px-2 py-1 rounded-pill">
                                <i class="fas fa-lock me-1"></i>Locked
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        @if(!$data['subject']->is_locked)
                        <a href="{{ route('lecturer.attendance.index', $data['subject']) }}"
                            class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-check-square me-2"></i>Mark Attendance
                        </a>
                        @endif
                        <a href="{{ route('lecturer.attendance.history', $data['subject']) }}"
                            class="btn btn-outline-primary btn-sm flex-grow-1">
                            <i class="fas fa-history me-2"></i>View History
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection