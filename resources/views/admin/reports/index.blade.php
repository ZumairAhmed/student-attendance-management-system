@extends('layouts.app')
@section('title', 'Generate Reports')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-file-pdf me-2 text-primary"></i>Generate Attendance Report</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.reports.pdf') }}" method="GET" target="_blank">
                    <div class="mb-4">
                        <label class="form-label">Select Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Select a Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }} ({{ $subject->code }}) — {{ $subject->batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="fas fa-file-download me-2"></i>Generate & Download PDF Report
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><i class="fas fa-info-circle me-2 text-primary"></i>Report Includes</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>All students in the selected subject's batch</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Present, Absent, Late counts per student</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Attendance percentage per student</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Highlights students below 80% threshold</li>
                    <li><i class="fas fa-check text-success me-2"></i>Printable A4 landscape PDF format</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
