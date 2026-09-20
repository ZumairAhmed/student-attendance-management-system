@extends('layouts.app')
@section('title', 'Edit Timetable')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2 text-primary"></i>Edit Timetable Info
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.timetables.update', $timetable) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="1st Semester" {{ $timetable->semester === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ $timetable->semester === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="3rd Semester" {{ $timetable->semester === '3rd Semester' ? 'selected' : '' }}>3rd Semester</option>
                                <option value="4th Semester" {{ $timetable->semester === '4th Semester' ? 'selected' : '' }}>4th Semester</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control"
                                value="{{ $timetable->academic_year }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Effective Date</label>
                            <input type="date" name="effective_date" class="form-control"
                                value="{{ $timetable->effective_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $timetable->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $timetable->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Select Days</label>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach($allDays as $day)
                                @php $checked = in_array($day, $timetable->days); @endphp
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="days[]" value="{{ $day }}"
                                        class="d-none day-check" id="day-{{ $day }}"
                                        {{ $checked ? 'checked' : '' }}>
                                    <span class="day-badge"
                                        style="display:inline-block;padding:8px 16px;border-radius:10px;border:2px solid {{ $checked ? '#3b82f6' : '#e2e8f0' }};font-size:13px;font-weight:600;color:{{ $checked ? '#1d4ed8' : '#64748b' }};background:{{ $checked ? '#eff6ff' : 'white' }};transition:all 0.2s;">
                                        {{ $day }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.timetables.show', $timetable) }}"
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