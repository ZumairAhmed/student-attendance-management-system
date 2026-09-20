<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\TimetableSlot;

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

    public function index()
    {
        $lecturer   = auth()->user();
        $timeSlots  = $this->timeSlots;
        $today      = now()->format('l'); // e.g. "Tuesday"

        // Get all timetables where this lecturer has slots
        $mySlots = TimetableSlot::whereHas('subject', function ($q) use ($lecturer) {
            $q->where('user_id', $lecturer->id);
        })
        ->whereHas('timetable', function ($q) {
            $q->where('status', 'active');
        })
        ->with(['timetable.batch', 'subject'])
        ->get();

        // Group by timetable
        $timetables = $mySlots->groupBy('timetable_id')->map(function ($slots) {
            return [
                'timetable' => $slots->first()->timetable,
                'slots'     => $slots,
            ];
        });

        // Today's classes
        $todayClasses = $mySlots->filter(function ($slot) use ($today) {
            return $slot->day === $today && !$slot->is_continuation;
        })->sortBy('start_time');

        // Build grid per timetable
        $grids = [];
        foreach ($timetables as $timetableId => $data) {
            $tt   = $data['timetable'];
            $days = $tt->days;
            $grid = [];
            foreach ($timeSlots as $startTime => $label) {
                $grid[$startTime] = [];
                foreach ($days as $day) {
                    $slot = $mySlots
                        ->where('timetable_id', $timetableId)
                        ->where('day', $day)
                        ->where('start_time', $startTime)
                        ->first();
                    $grid[$startTime][$day] = $slot;
                }
            }
            $grids[$timetableId] = [
                'timetable' => $tt,
                'days'      => $days,
                'grid'      => $grid,
            ];
        }

        return view('lecturer.timetable',
            compact('grids', 'timeSlots', 'todayClasses', 'today'));
    }
}