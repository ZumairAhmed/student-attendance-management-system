@extends('layouts.app')
@section('title', 'Low Attendance Alerts')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="text-muted mb-0">Students below 80% attendance threshold</h6>
    <span class="badge bg-danger px-3 py-2" style="font-size:14px;">
        {{ count($flagged) }} Alert(s)
    </span>
</div>

@if(count($flagged) === 0)
    <div class="card text-center p-5">
        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
        <h5 class="fw-bold text-success">All Good!</h5>
        <p class="text-muted">No students are below the 80% attendance threshold.</p>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Flagged Students
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Index No</th>
                        <th>Subject</th>
                        <th>Batch</th>
                        <th>Present</th>
                        <th>Total</th>
                        <th>Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flagged as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ route('admin.students.show', $item['student']) }}" class="text-decoration-none fw-bold">
                                {{ $item['student']->full_name }}
                            </a>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $item['student']->index_no }}</span></td>
                        <td>{{ $item['subject']->name }}</td>
                        <td>{{ $item['subject']->batch->name }}</td>
                        <td>{{ $item['present'] }}</td>
                        <td>{{ $item['total'] }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="percentage-bar" style="width:100px;">
                                    <div class="percentage-fill" style="width:{{ $item['percentage'] }}%;background:#ef4444;"></div>
                                </div>
                                <span style="font-weight:700;color:#dc2626;">{{ $item['percentage'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection