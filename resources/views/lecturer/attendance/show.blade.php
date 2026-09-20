@extends('layouts.app')
@section('title', 'Session Details')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="text-muted mb-0">
            {{ $subject->name }} —
            {{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}
            @if($session->notes) <small class="text-muted">· {{ $session->notes }}</small> @endif
        </h6>
    </div>
    <a href="{{ route('lecturer.attendance.history', $subject) }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2 text-primary"></i>Attendance for this Session</span>
        @if(!$subject->is_locked)
        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#editForm">
            <i class="fas fa-edit me-1"></i>Edit Attendance
        </button>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Index No</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $attendance->student->full_name }}</strong></td>
                    <td><span class="badge bg-light text-dark">{{ $attendance->student->index_no }}</span></td>
                    <td>
                        @if($attendance->status === 'Present')
                            <span class="badge badge-present px-2 py-1 rounded-pill">Present</span>
                        @elseif($attendance->status === 'Absent')
                            <span class="badge badge-absent px-2 py-1 rounded-pill">Absent</span>
                        @else
                            <span class="badge badge-late px-2 py-1 rounded-pill">Late</span>
                        @endif
                        @if($attendance->edit_reason)
                            <small class="text-muted ms-2">Edited: {{ $attendance->edit_reason }}</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(!$subject->is_locked)
<div class="collapse" id="editForm">
    <div class="card">
        <div class="card-header"><i class="fas fa-edit me-2 text-warning"></i>Edit Attendance</div>
        <div class="card-body p-4">
            <form action="{{ route('lecturer.attendance.update', [$subject, $session]) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Reason for Edit <span class="text-danger">*</span></label>
                    <input type="text" name="edit_reason" class="form-control"
                        placeholder="e.g. Marked wrong by mistake" required>
                </div>
                <table class="table">
                    <thead>
                        <tr><th>Student</th><th>Present</th><th>Absent</th><th>Late</th></tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td><strong>{{ $attendance->student->full_name }}</strong></td>
                            <td><input type="radio" name="attendance[{{ $attendance->id }}]" value="Present"
                                class="form-check-input" {{ $attendance->status === 'Present' ? 'checked' : '' }}></td>
                            <td><input type="radio" name="attendance[{{ $attendance->id }}]" value="Absent"
                                class="form-check-input" {{ $attendance->status === 'Absent' ? 'checked' : '' }}></td>
                            <td><input type="radio" name="attendance[{{ $attendance->id }}]" value="Late"
                                class="form-check-input" {{ $attendance->status === 'Late' ? 'checked' : '' }}></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endif

@endsection