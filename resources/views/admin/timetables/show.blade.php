@extends('layouts.app')
@section('title', 'Timetable — ' . $timetable->batch->name)
@section('subtitle', $timetable->semester . ' · ' . $timetable->academic_year)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.timetables.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('admin.timetables.edit', $timetable) }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-edit me-1"></i>Edit Info
        </a>
        <a href="{{ route('admin.timetables.print', $timetable) }}" target="_blank"
            class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-print me-1"></i>Print
        </a>
    </div>
</div>

<!-- ADD SLOT FORM -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-plus-circle me-2 text-primary"></i>Add Time Slot
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.timetables.slots.add', $timetable) }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->code }} — {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Day</label>
                    <select name="day" class="form-select" required>
                        <option value="">Select Day</option>
                        @foreach($allDays as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Time</label>
                    <select name="start_time" class="form-select" required>
                        <option value="">Select Time</option>
                        @foreach($timeSlots as $key => $label)
                            @if($key !== '12.30')
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Duration (hrs)</label>
                    <select name="duration" class="form-select" required>
                        <option value="1">1 Hour</option>
                        <option value="2">2 Hours</option>
                        <option value="3">3 Hours</option>
                        <option value="4">4 Hours</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-1"></i>Add Slot
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- TIMETABLE GRID -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-table me-2 text-primary"></i>
            Class Timetable — {{ $timetable->batch->name }}
        </span>
        <small class="text-muted">Effective: {{ \Carbon\Carbon::parse($timetable->effective_date)->format('d M Y') }}</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="min-width:600px;">
                <thead>
                    <tr style="background:#0a1e5e;">
                        <th style="color:black;font-size:12px;padding:12px 16px;width:140px;">Time</th>
                        @foreach($allDays as $day)
                        <th style="color:black;font-size:12px;padding:12px 16px;text-align:center;">
                            {{ $day }}
                        </th>
                        @endforeach
                        <th style="color:black;font-size:12px;padding:12px 16px;width:80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $startTime => $label)
                    @if($startTime === '12.30')
                    <tr style="background:#fef9c3;">
                        <td style="font-size:12px;font-weight:600;color:#92400e;padding:8px 16px;">
                            {{ $label }}
                        </td>
                        @foreach($allDays as $day)
                        <td style="text-align:center;font-size:12px;color:#92400e;font-weight:600;">
                            LUNCH BREAK
                        </td>
                        @endforeach
                        <td></td>
                    </tr>
                    @else
                    <tr>
                        <td style="font-size:12px;font-weight:700;color:#374151;padding:10px 16px;background:#fafbfc;">
                            {{ $label }}
                        </td>
                        @foreach($allDays as $day)
                        <td style="text-align:center;padding:10px 8px;">
                            @if(isset($grid[$startTime][$day]) && $grid[$startTime][$day])
                                @php $slot = $grid[$startTime][$day]; @endphp
                                @if($slot->is_continuation)
                                    <span style="font-size:12px;color:#64748b;font-style:italic;">-Do-</span>
                                @else
                                    <div style="background:#eff6ff;border-radius:8px;padding:6px 8px;border-left:3px solid #3b82f6;">
                                        <div style="font-size:12px;font-weight:700;color:#1d4ed8;">
                                            {{ $slot->subject->code }}
                                        </div>
                                        <div style="font-size:10px;color:#64748b;margin-top:2px;">
                                            {{ Str::limit($slot->subject->name, 15) }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <span style="color:#e2e8f0;font-size:18px;">—</span>
                            @endif
                        </td>
                        @endforeach
                        <td style="text-align:center;padding:6px;">
                            @foreach($allDays as $day)
                                @if(isset($grid[$startTime][$day]) && $grid[$startTime][$day] && !$grid[$startTime][$day]->is_continuation)
                                <form action="{{ route('admin.timetables.slots.delete', [$timetable, $grid[$startTime][$day]]) }}"
                                    method="POST" onsubmit="return confirm('Remove this slot and its continuations?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" style="font-size:10px;padding:2px 6px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SUBJECT TABLE -->
<div class="card">
    <div class="card-header"><i class="fas fa-list me-2 text-primary"></i>Subject Details</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Lecturer</th>
                    <th>Hours</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subjectSlots = $timetable->slots->where('is_continuation', false)->groupBy('subject_id');
                @endphp
                @forelse($subjectSlots as $subjectId => $slots)
                @php $subject = $slots->first()->subject; @endphp
                <tr>
                    <td><span class="badge badge-info px-2 py-1">{{ $subject->code }}</span></td>
                    <td><strong>{{ $subject->name }}</strong></td>
                    <td>{{ $subject->lecturer->name ?? 'Not Assigned' }}</td>
                    <td>
                        <span class="badge badge-present px-2 py-1">
                            {{ $timetable->slots->where('subject_id', $subjectId)->count() }} hrs
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No slots added yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection