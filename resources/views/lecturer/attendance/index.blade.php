@extends('layouts.app')
@section('title', 'Mark Attendance')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="text-muted mb-0">{{ $subject->name }} — {{ $subject->batch->name }}</h6>
    </div>
    <a href="{{ route('lecturer.attendance.history', $subject) }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-history me-2"></i>View History
    </a>
</div>

@if($todaySession)
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Attendance already marked for today ({{ \Carbon\Carbon::parse($todaySession->date)->format('d M Y') }}).
        <a href="{{ route('lecturer.attendance.show', [$subject, $todaySession]) }}" class="alert-link ms-2">View it →</a>
    </div>
@endif

<div class="card">
    <div class="card-header"><i class="fas fa-check-square me-2 text-primary"></i>Mark Attendance</div>
    <div class="card-body p-4">
        <form action="{{ route('lecturer.attendance.store', $subject) }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Session Date</label>
                    <input type="date" name="date" class="form-control"
                        value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Session Notes <span class="text-muted">(Optional)</span></label>
                    <input type="text" name="notes" class="form-control"
                        placeholder="e.g. Practical session, Guest lecture">
                </div>
            </div>

            <div class="d-flex gap-2 mb-3">
                <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('Present')">
                    <i class="fas fa-check me-1"></i>All Present
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('Absent')">
                    <i class="fas fa-times me-1"></i>All Absent
                </button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Index No</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Late</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $student->full_name }}</strong></td>
                        <td><span class="badge bg-light text-dark">{{ $student->index_no }}</span></td>
                        <td>
                            <input type="radio" name="attendance[{{ $student->id }}]"
                                value="Present" class="form-check-input status-radio" checked>
                        </td>
                        <td>
                            <input type="radio" name="attendance[{{ $student->id }}]"
                                value="Absent" class="form-check-input status-radio">
                        </td>
                        <td>
                            <input type="radio" name="attendance[{{ $student->id }}]"
                                value="Late" class="form-check-input status-radio">
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No students in this batch.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if($students->count() > 0)
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Save Attendance
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        if (radio.value === status) radio.checked = true;
    });
}
</script>
@endsection