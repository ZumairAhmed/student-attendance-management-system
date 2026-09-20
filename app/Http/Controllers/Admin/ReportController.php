<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Session;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $batches  = Batch::where('status', 'active')->get();
        $subjects = Subject::with(['batch', 'lecturer'])->get();
        return view('admin.reports.index', compact('batches', 'subjects'));
    }

    public function generatePdf(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $subject  = Subject::with(['batch', 'lecturer'])->findOrFail($request->subject_id);
        $sessions = Session::where('subject_id', $subject->id)->orderBy('date')->get();
        $students = Student::where('batch_id', $subject->batch_id)
            ->where('status', 'active')->get();

        $reportData = $students->map(function ($student) use ($sessions, $subject) {
            $totalSessions = $sessions->count();
            $present = Attendance::whereIn('session_id', $sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->whereIn('status', ['Present', 'Late'])->count();
            $absent = Attendance::whereIn('session_id', $sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->where('status', 'Absent')->count();
            $late = Attendance::whereIn('session_id', $sessions->pluck('id'))
                ->where('student_id', $student->id)
                ->where('status', 'Late')->count();
            $percentage = $totalSessions > 0
                ? round(($present / $totalSessions) * 100, 1) : 100;
            return [
                'student'    => $student,
                'present'    => $present,
                'absent'     => $absent,
                'late'       => $late,
                'total'      => $totalSessions,
                'percentage' => $percentage,
                'flagged'    => $percentage < 80,
            ];
        });

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.reports.pdf',
            compact('subject', 'sessions', 'reportData'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('attendance_report_' . $subject->code . '.pdf');
    }
}