@extends('layouts.app')
@section('title', 'Notifications')
@section('subtitle', 'Your alerts and reminders')
@section('content')

@php
    $lecturer   = auth()->user();
    $subjects   = \App\Models\Subject::where('user_id', $lecturer->id)->with(['sessions','batch'])->get();
    $today      = now()->format('l');
    $todayDate  = now()->toDateString();

    $notifications = [];

    // Low attendance alerts
    foreach ($subjects as $subject) {
        $totalSessions = $subject->sessions->count();
        if ($totalSessions === 0) continue;

        $students = \App\Models\Student::where('batch_id', $subject->batch_id)
            ->where('status','active')->get();

        foreach ($students as $student) {
            $present = \App\Models\Attendance::whereIn('session_id', $subject->sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->whereIn('status', ['Present','Late'])
                ->count();
            $pct = round(($present / $totalSessions) * 100, 1);
            if ($pct < 80) {
                $notifications[] = [
                    'type'    => 'warning',
                    'icon'    => 'fas fa-exclamation-triangle',
                    'color'   => '#f97316',
                    'bg'      => '#fff7ed',
                    'title'   => 'Low Attendance Alert',
                    'message' => $student->full_name . ' has only ' . $pct . '% attendance in ' . $subject->name,
                    'time'    => 'Ongoing',
                ];
            }
        }

        // Today's class reminder
        $todaySlot = \App\Models\TimetableSlot::whereHas('timetable', function($q) use($subject) {
            $q->where('status','active');
        })->where('subject_id', $subject->id)
          ->where('day', $today)
          ->where('is_continuation', false)
          ->first();

        if ($todaySlot) {
            $alreadyMarked = \App\Models\Session::where('subject_id', $subject->id)
                ->whereDate('date', $todayDate)->exists();

            $notifications[] = [
                'type'    => $alreadyMarked ? 'success' : 'info',
                'icon'    => $alreadyMarked ? 'fas fa-check-circle' : 'fas fa-bell',
                'color'   => $alreadyMarked ? '#16a34a' : '#3b82f6',
                'bg'      => $alreadyMarked ? '#f0fdf4' : '#eff6ff',
                'title'   => $alreadyMarked ? 'Attendance Marked' : 'Class Today',
                'message' => $subject->name . ' (' . $subject->batch->name . ') — ' .
                    ($alreadyMarked ? 'Attendance already marked for today.' : 'You have a class today. Don\'t forget to mark attendance!'),
                'time'    => 'Today',
            ];
        }

        // Sessions not marked
        $lastWeek = now()->subDays(7)->toDateString();
        $timetableSlots = \App\Models\TimetableSlot::whereHas('timetable', function($q) use($subject) {
            $q->where('status','active');
        })->where('subject_id', $subject->id)
          ->where('is_continuation', false)
          ->get();

        // Check if any recent date was missed
        foreach ($timetableSlots as $ts) {
            $recentDates = [];
            $date = now();
            for ($i = 1; $i <= 7; $i++) {
                $checkDate = now()->subDays($i);
                if ($checkDate->format('l') === $ts->day) {
                    $recentDates[] = $checkDate->toDateString();
                }
            }
            foreach ($recentDates as $checkDate) {
                if ($checkDate === $todayDate) continue;
                $marked = \App\Models\Session::where('subject_id', $subject->id)
                    ->whereDate('date', $checkDate)->exists();
                if (!$marked) {
                    $notifications[] = [
                        'type'    => 'danger',
                        'icon'    => 'fas fa-times-circle',
                        'color'   => '#dc2626',
                        'bg'      => '#fef2f2',
                        'title'   => 'Missed Attendance',
                        'message' => 'Attendance not marked for ' . $subject->name . ' on ' .
                            \Carbon\Carbon::parse($checkDate)->format('d M Y') . ' (' . \Carbon\Carbon::parse($checkDate)->format('l') . ')',
                        'time'    => \Carbon\Carbon::parse($checkDate)->diffForHumans(),
                    ];
                }
            }
        }
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h6 class="text-muted mb-0">All your alerts and reminders</h6>
    <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
        {{ count($notifications) }} notification(s)
    </span>
</div>

@if(count($notifications) === 0)
<div class="card text-center p-5">
    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
    <h5 class="fw-bold text-success">All Clear!</h5>
    <p class="text-muted mb-0">No notifications right now. Everything is up to date!</p>
</div>
@else
<div class="d-flex flex-column gap-3">
    @foreach($notifications as $notif)
    <div style="background:{{ $notif['bg'] }};border-radius:16px;padding:18px 20px;border-left:4px solid {{ $notif['color'] }};display:flex;align-items:flex-start;gap:16px;">
        <div style="width:42px;height:42px;background:white;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <i class="{{ $notif['icon'] }}" style="color:{{ $notif['color'] }};font-size:18px;"></i>
        </div>
        <div class="flex-grow-1">
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:3px;">
                {{ $notif['title'] }}
            </div>
            <div style="font-size:13px;color:#374151;line-height:1.5;">
                {{ $notif['message'] }}
            </div>
        </div>
        <div style="font-size:11px;color:#94a3b8;white-space:nowrap;flex-shrink:0;">
            {{ $notif['time'] }}
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection