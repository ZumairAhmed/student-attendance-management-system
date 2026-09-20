<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Session;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $students = Student::where('batch_id', $subject->batch_id)
            ->where('status', 'active')->get();
        $todaySession = Session::where('subject_id', $subject->id)
            ->whereDate('date', today())->first();
        return view('lecturer.attendance.index',
            compact('subject', 'students', 'todaySession'));
    }

    public function store(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);

        if ($subject->is_locked) {
            return back()->with('error', 'This subject is locked by admin.');
        }

        $request->validate([
            'date'         => 'required|date',
            'notes'        => 'nullable|string|max:255',
            'attendance'   => 'required|array',
            'attendance.*' => 'in:Present,Absent,Late',
        ]);

        $existing = Session::where('subject_id', $subject->id)
            ->whereDate('date', $request->date)->first();

        if ($existing) {
            return back()->with('error', 'Attendance already marked for this date!');
        }

        $session = Session::create([
            'subject_id' => $subject->id,
            'date'       => $request->date,
            'notes'      => $request->notes,
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::create([
                'session_id' => $session->id,
                'student_id' => $studentId,
                'status'     => $status,
            ]);
        }

        return redirect()->route('lecturer.attendance.history', $subject->id)
            ->with('success', 'Attendance marked successfully!');
    }

    public function history(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $sessions = Session::where('subject_id', $subject->id)
            ->with('attendances.student')
            ->latest('date')->get();

        $students = Student::where('batch_id', $subject->batch_id)
            ->where('status', 'active')->get();

        $attendanceSummary = $students->map(function ($student) use ($subject, $sessions) {
            $totalSessions = $sessions->count();
            $present = Attendance::whereIn('session_id', $sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->whereIn('status', ['Present', 'Late'])->count();
            $percentage = $totalSessions > 0
                ? round(($present / $totalSessions) * 100, 1) : 100;
            return [
                'student'    => $student,
                'present'    => $present,
                'total'      => $totalSessions,
                'percentage' => $percentage,
                'flagged'    => $percentage < 80,
            ];
        });

        return view('lecturer.attendance.history',
            compact('subject', 'sessions', 'attendanceSummary'));
    }

    public function show(Subject $subject, Session $session)
    {
        $this->authorizeSubject($subject);
        $attendances = Attendance::where('session_id', $session->id)
            ->with('student')->get();
        return view('lecturer.attendance.show',
            compact('subject', 'session', 'attendances'));
    }

    public function update(Request $request, Subject $subject, Session $session)
    {
        $this->authorizeSubject($subject);

        if ($subject->is_locked) {
            return back()->with('error', 'This subject is locked by admin.');
        }

        $request->validate([
            'attendance'   => 'required|array',
            'attendance.*' => 'in:Present,Absent,Late',
            'edit_reason'  => 'required|string|max:255',
        ]);

        foreach ($request->attendance as $attendanceId => $status) {
            Attendance::where('id', $attendanceId)->update([
                'status'      => $status,
                'edit_reason' => $request->edit_reason,
            ]);
        }

        return redirect()->route('lecturer.attendance.history', $subject->id)
            ->with('success', 'Attendance updated successfully!');
    }

    private function authorizeSubject(Subject $subject)
    {
        if ($subject->user_id !== auth()->id()) {
            abort(403, 'You are not assigned to this subject.');
        }
    }
}