@extends('layouts.app')
@section('title', 'Attendance History')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="text-muted mb-0">{{ $subject->name }} — {{ $subject->batch->name }}</h6>
    @if(!$subject->is_locked)
    <a href="{{ route('lecturer.attendance.index', $subject) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-2"></i>Mark New Session
    </a>
    @endif
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><i class="fas fa-users me-2 text-primary"></i>Attendance Summary</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Index No</th>
                            <th>Present</th>
                            <th>Total Sessions</th>
                            <th>Attendance %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendanceSummary as $summary)
                        <tr>
                            <td><strong>{{ $summary['student']->full_name }}</strong></td>
                            <td><span class="badge bg-light text-dark">{{ $summary['student']->index_no }}</span></td>
                            <td>{{ $summary['present'] }}</td>
                            <td>{{ $summary['total'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="percentage-bar" style="width:80px;">
                                        <div class="percentage-fill"
                                            style="width:{{ $summary['percentage'] }}%;background:{{ $summary['flagged'] ? '#ef4444' : '#22c55e' }};">
                                        </div>
                                    </div>
                                    <span style="font-weight:700;color:{{ $summary['flagged'] ? '#dc2626' : '#16a34a' }};">
                                        {{ $summary['percentage'] }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($summary['flagged'])
                                    <span class="badge badge-absent px-2 py-1 rounded-pill">Below 80%</span>
                                @else
                                    <span class="badge badge-present px-2 py-1 rounded-pill">Good</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-calendar me-2 text-primary"></i>All Sessions</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</strong></td>
                    <td>{{ $session->notes ?? '-' }}</td>
                    <td><span class="badge badge-present px-2 py-1">{{ $session->attendances->where('status','Present')->count() }}</span></td>
                    <td><span class="badge badge-absent px-2 py-1">{{ $session->attendances->where('status','Absent')->count() }}</span></td>
                    <td><span class="badge badge-late px-2 py-1">{{ $session->attendances->where('status','Late')->count() }}</span></td>
                    <td>
                        <a href="{{ route('lecturer.attendance.show', [$subject, $session]) }}"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No sessions marked yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>
@endsection