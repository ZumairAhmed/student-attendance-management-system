<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use App\Models\Batch;
use App\Models\Subject;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    protected $timeSlots = [
        '8.30'  => '8.30 - 9.30',
        '9.30'  => '9.30 - 10.30',
        '10.30' => '10.30 - 11.30',
        '11.30' => '11.30 - 12.30',
        '12.30' => '12.30 - 1.00 (Break)',
        '13.00' => '1.00 - 2.00',
        '14.00' => '2.00 - 3.00',
        '15.00' => '3.00 - 4.00',
        '16.00' => '4.00 - 5.00',
    ];

    protected $allDays = [
        'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    public function index()
    {
        $timetables = Timetable::with('batch')->latest()->get();
        return view('admin.timetables.index', compact('timetables'));
    }

    public function create()
    {
        $batches   = Batch::where('status', 'active')->get();
        $allDays   = $this->allDays;
        $timeSlots = $this->timeSlots;
        return view('admin.timetables.create', compact('batches', 'allDays', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id'       => 'required|exists:batches,id',
            'semester'       => 'required|string',
            'academic_year'  => 'required|string',
            'effective_date' => 'required|date',
            'days'           => 'required|array|min:1',
        ]);

        $timetable = Timetable::create([
            'batch_id'       => $request->batch_id,
            'semester'       => $request->semester,
            'academic_year'  => $request->academic_year,
            'effective_date' => $request->effective_date,
            'days'           => $request->days,
            'status'         => 'active',
        ]);

        return redirect()->route('admin.timetables.show', $timetable)
            ->with('success', 'Timetable created! Now add your time slots.');
    }

    public function show(Timetable $timetable)
    {
        $timetable->load(['batch', 'slots.subject.lecturer']);
        $subjects  = Subject::where('batch_id', $timetable->batch_id)
            ->with('lecturer')->get();
        $timeSlots = $this->timeSlots;
        $allDays   = $timetable->days;

        // Build grid
        $grid = [];
        foreach ($timeSlots as $startTime => $label) {
            $grid[$startTime] = [];
            foreach ($allDays as $day) {
                $slot = $timetable->slots
                    ->where('day', $day)
                    ->where('start_time', $startTime)
                    ->first();
                $grid[$startTime][$day] = $slot;
            }
        }

        return view('admin.timetables.show',
            compact('timetable', 'grid', 'timeSlots', 'allDays', 'subjects'));
    }

    public function addSlot(Request $request, Timetable $timetable)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'day'        => 'required|string',
            'start_time' => 'required|string',
            'duration'   => 'required|integer|min:1|max:5',
        ]);

        $timeKeys   = array_keys($this->timeSlots);
        $startIndex = array_search($request->start_time, $timeKeys);
        $duration   = (int)$request->duration;
        $slotGroup  = time();

        // Check for conflicts
        for ($i = 0; $i < $duration; $i++) {
            if ($startIndex + $i >= count($timeKeys)) break;
            $currentTime = $timeKeys[$startIndex + $i];

            // Skip break slot
            if ($currentTime === '12.30') {
                $duration++;
                continue;
            }

            $existing = TimetableSlot::where('timetable_id', $timetable->id)
                ->where('day', $request->day)
                ->where('start_time', $currentTime)
                ->first();

            if ($existing) {
                return back()->with('error',
                    "Time slot conflict at {$this->timeSlots[$currentTime]} on {$request->day}!");
            }
        }

        // Check lecturer conflict
        $subject    = Subject::find($request->subject_id);
        $lecturerId = $subject->user_id;

        if ($lecturerId) {
            for ($i = 0; $i < $duration; $i++) {
                if ($startIndex + $i >= count($timeKeys)) break;
                $currentTime = $timeKeys[$startIndex + $i];
                if ($currentTime === '12.30') continue;

                $lecturerConflict = TimetableSlot::whereHas('timetable', function ($q) use ($timetable) {
                    $q->where('status', 'active');
                })
                ->whereHas('subject', function ($q) use ($lecturerId) {
                    $q->where('user_id', $lecturerId);
                })
                ->where('day', $request->day)
                ->where('start_time', $currentTime)
                ->where('timetable_id', $timetable->id)
                ->first();

                if ($lecturerConflict) {
                    return back()->with('error',
                        "Lecturer conflict! This lecturer already has a class at {$this->timeSlots[$currentTime]} on {$request->day}!");
                }
            }
        }

        // Create slots
        $slotCount = 0;
        for ($i = 0; $i < $duration; $i++) {
            if ($startIndex + $i >= count($timeKeys)) break;
            $currentTime = $timeKeys[$startIndex + $i];

            // Auto skip break
            if ($currentTime === '12.30') {
                $duration++;
                continue;
            }

            $nextIndex = $startIndex + $i + 1;
            $endTime   = $nextIndex < count($timeKeys)
                ? $timeKeys[$nextIndex] : '17.00';

            TimetableSlot::create([
                'timetable_id'    => $timetable->id,
                'subject_id'      => $request->subject_id,
                'day'             => $request->day,
                'start_time'      => $currentTime,
                'end_time'        => $endTime,
                'is_continuation' => $slotCount > 0,
                'slot_group'      => $slotGroup,
            ]);
            $slotCount++;
        }

        return back()->with('success', 'Slot added successfully!');
    }

    public function deleteSlot(Timetable $timetable, TimetableSlot $slot)
    {
        // Delete entire group
        TimetableSlot::where('timetable_id', $timetable->id)
            ->where('slot_group', $slot->slot_group)
            ->delete();

        return back()->with('success', 'Slot removed successfully!');
    }

    public function edit(Timetable $timetable)
    {
        $batches  = Batch::where('status', 'active')->get();
        $allDays  = $this->allDays;
        return view('admin.timetables.edit', compact('timetable', 'batches', 'allDays'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'semester'       => 'required|string',
            'academic_year'  => 'required|string',
            'effective_date' => 'required|date',
            'days'           => 'required|array|min:1',
            'status'         => 'required|in:active,inactive',
        ]);

        $timetable->update([
            'semester'       => $request->semester,
            'academic_year'  => $request->academic_year,
            'effective_date' => $request->effective_date,
            'days'           => $request->days,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.timetables.show', $timetable)
            ->with('success', 'Timetable updated successfully!');
    }

    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        return redirect()->route('admin.timetables.index')
            ->with('success', 'Timetable deleted successfully!');
    }

    public function printView(Timetable $timetable)
    {
        $timetable->load(['batch', 'slots.subject.lecturer']);
        $timeSlots = $this->timeSlots;
        $allDays   = $timetable->days;

        $grid = [];
        foreach ($timeSlots as $startTime => $label) {
            $grid[$startTime] = [];
            foreach ($allDays as $day) {
                $slot = $timetable->slots
                    ->where('day', $day)
                    ->where('start_time', $startTime)
                    ->first();
                $grid[$startTime][$day] = $slot;
            }
        }

        $uniqueSubjects = $timetable->slots
            ->where('is_continuation', false)
            ->groupBy('subject_id')
            ->map(function ($slots) {
                $slot    = $slots->first();
                $subject = $slot->subject;
                $hours   = $slots->count();
                return [
                    'subject' => $subject,
                    'hours'   => $hours,
                ];
            });

        return view('admin.timetables.print',
            compact('timetable', 'grid', 'timeSlots', 'allDays', 'uniqueSubjects'));
    }
}