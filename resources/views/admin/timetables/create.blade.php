@extends('layouts.app')
@section('title', 'Create Timetable')
@section('subtitle', 'Set up a new semester timetable')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-plus me-2 text-primary"></i>New Timetable
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.timetables.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Batch</label>
                            <select name="batch_id" class="form-select" required>
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">Select Semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="3rd Semester">3rd Semester</option>
                                <option value="4th Semester">4th Semester</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control"
                                placeholder="e.g. 2025/2026" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Effective Date</label>
                            <input type="date" name="effective_date" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Select Days</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach($allDays as $day)
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="days[]" value="{{ $day }}"
                                        class="d-none day-check" id="day-{{ $day }}">
                                    <span class="day-badge" for="day-{{ $day }}"
                                        style="display:inline-block;padding:8px 16px;border-radius:10px;border:2px solid #e2e8f0;font-size:13px;font-weight:600;color:#64748b;transition:all 0.2s;">
                                        {{ $day }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('days')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-arrow-right me-2"></i>Create & Add Slots
                        </button>
                        <a href="{{ route('admin.timetables.index') }}"
                            class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
document.querySelectorAll('.day-check').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const badge = this.nextElementSibling;
        if (this.checked) {
            badge.style.background = '#eff6ff';
            badge.style.borderColor = '#3b82f6';
            badge.style.color = '#1d4ed8';
        } else {
            badge.style.background = 'white';
            badge.style.borderColor = '#e2e8f0';
            badge.style.color = '#64748b';
        }
    });
});
</script>
@endsection