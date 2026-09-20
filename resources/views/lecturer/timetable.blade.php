@extends('layouts.app')
@section('title', 'My Timetable')
@section('subtitle', 'Your weekly schedule and today\'s classes')
@section('content')

{{-- TODAY'S CLASSES --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-sun me-2 text-warning"></i>Today's Classes — {{ $today }}</span>
        <span class="badge badge-info px-2 py-1 rounded-pill">{{ $todayClasses->count() }} class(es)</span>
    </div>
    <div class="card-body">
        @if($todayClasses->count() === 0)
        <div class="text-center py-3 text-muted">
            <i class="fas fa-coffee fa-2x mb-2 d-block"></i>
            No classes today! Enjoy your day.
        </div>
        @else
        <div class="row g-3">
            @foreach($todayClasses as $slot)
            <div class="col-md-4">
                <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:14px;padding:16px;border-left:4px solid #3b82f6;">
                    <div style="font-size:13px;font-weight:700;color:#1d4ed8;">
                        {{ $slot->subject->code }}
                    </div>
                    <div style="font-size:14px;font-weight:800;color:#0f172a;margin:4px 0;">
                        {{ $slot->subject->name }}
                    </div>
                    <div style="font-size:12px;color:#64748b;">
                        <i class="fas fa-clock me-1"></i>
                        @php
                            $times = [
                                '8.30'=>'8.30 - 9.30','9.30'=>'9.30 - 10.30',
                                '10.30'=>'10.30 - 11.30','11.30'=>'11.30 - 12.30',
                                '13.00'=>'1.00 - 2.00','14.00'=>'2.00 - 3.00',
                                '15.00'=>'3.00 - 4.00','16.00'=>'4.00 - 5.00',
                            ];
                        @endphp
                        {{ $times[$slot->start_time] ?? $slot->start_time }}
                    </div>
                    <div style="font-size:12px;color:#64748b;margin-top:3px;">
                        <i class="fas fa-users me-1"></i>
                        {{ $slot->timetable->batch->name }}
                    </div>
                    <a href="{{ route('lecturer.attendance.index', $slot->subject) }}"
                        class="btn btn-primary btn-sm w-100 mt-2" style="font-size:12px;">
                        <i class="fas fa-check-square me-1"></i>Mark Attendance
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- TIMETABLES PER BATCH --}}
@if(count($grids) === 0)
<div class="card text-center p-5">
    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
    <h5 class="fw-bold text-muted">No Timetable Assigned</h5>
    <p class="text-muted mb-0">Contact the administrator to set up your timetable.</p>
</div>
@else
@foreach($grids as $timetableId => $data)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-calendar-week me-2 text-primary"></i>
            {{ $data['timetable']->batch->name }} —
            {{ $data['timetable']->semester }}
            {{ $data['timetable']->academic_year }}
        </span>
        <small class="text-muted">
            Effective: {{ \Carbon\Carbon::parse($data['timetable']->effective_date)->format('d M Y') }}
        </small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" style="min-width:500px;">
                <thead>
                    <tr style="background:#0a1e5e;">
                        <th style="color:black;font-size:11px;padding:10px 14px;width:130px;">Time</th>
                        @foreach($data['days'] as $day)
                        <th style="color:black;font-size:11px;padding:10px;text-align:center;
                            {{ $day === $today ? 'background:#1d4ed8;' : '' }}">
                            {{ $day }}
                            @if($day === $today)
                                <span style="display:block;font-size:9px;color:#93c5fd;">TODAY</span>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $startTime => $label)
                    @if($startTime === '12.30')
                    <tr style="background:#fef9c3;">
                        <td style="font-size:11px;font-weight:600;color:#92400e;padding:7px 14px;">
                            12.30 - 1.00
                        </td>
                        @foreach($data['days'] as $day)
                        <td style="text-align:center;font-size:11px;color:#92400e;">BREAK</td>
                        @endforeach
                    </tr>
                    @else
                    <tr style="{{ in_array($today, $data['days']) && $today === $today ? '' : '' }}">
                        <td style="font-size:11px;font-weight:700;color:#374151;padding:9px 14px;background:#fafbfc;">
                            {{ $label }}
                        </td>
                        @foreach($data['days'] as $day)
                        @php $slot = $data['grid'][$startTime][$day] ?? null; @endphp
                        <td style="text-align:center;padding:8px;{{ $day === $today ? 'background:#f0f9ff;' : '' }}">
                            @if($slot)
                                @if($slot->is_continuation)
                                    <span style="font-size:11px;color:#64748b;font-style:italic;">-Do-</span>
                                @else
                                    <div style="background:#eff6ff;border-radius:8px;padding:5px 6px;border-left:3px solid #3b82f6;">
                                        <div style="font-size:11px;font-weight:700;color:#1d4ed8;">
                                            {{ $slot->subject->code }}
                                        </div>
                                        <div style="font-size:10px;color:#64748b;">
                                            {{ Str::limit($slot->subject->name, 12) }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <span style="color:#e2e8f0;">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection