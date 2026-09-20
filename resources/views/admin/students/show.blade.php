@extends('layouts.app')
@section('title', 'Student Profile')
@section('content')

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="mx-auto mb-3" style="width:80px;height:80px;background:linear-gradient(135deg,#1e50a0,#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-graduate fa-2x text-white"></i>
            </div>
            <h5 class="fw-bold">{{ $student->full_name }}</h5>
            <p class="text-muted mb-1">{{ $student->index_no }}</p>
            <span class="badge badge-present px-3 py-1 rounded-pill">{{ $student->batch->name }}</span>
            <hr>
            <div class="text-start">
                <p class="mb-1 small"><i class="fas fa-envelope me-2 text-muted"></i>{{ $student->email ?? 'N/A' }}</p>
                <p class="mb-0 small"><i class="fas fa-phone me-2 text-muted"></i>{{ $student->phone ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar me-2 text-primary"></i>Attendance by Subject</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Present</th>
                            <th>Total</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $data)
                        <tr>
                            <td><strong>{{ $data['subject']->name }}</strong><br>
                                <small class="text-muted">{{ $data['subject']->code }}</small></td>
                            <td>{{ $data['present'] }}</td>
                            <td>{{ $data['total'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="percentage-bar flex-grow-1">
                                        <div class="percentage-fill"
                                            style="width:{{ $data['percentage'] }}%;background:{{ $data['flagged'] ? '#ef4444' : '#22c55e' }}">
                                        </div>
                                    </div>
                                    <span style="font-size:13px;font-weight:700;color:{{ $data['flagged'] ? '#dc2626' : '#16a34a' }}">
                                        {{ $data['percentage'] }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($data['flagged'])
                                    <span class="badge badge-absent px-2 py-1 rounded-pill">Below 80%</span>
                                @else
                                    <span class="badge badge-present px-2 py-1 rounded-pill">Good</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No subjects found for this batch.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Students
    </a>
</div>
@endsection