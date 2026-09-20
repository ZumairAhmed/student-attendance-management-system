<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\Session;
use App\Models\Attendance;

class DashboardController extends Controller
{
   public function index()
{
    $totalStudents  = Student::where('status', 'active')->count();
    $totalBatches   = Batch::where('status', 'active')->count();
    $totalSubjects  = Subject::count();
    $totalLecturers = User::where('role', 'lecturer')->where('status', 'active')->count();
    $todaySessions  = Session::whereDate('date', today())->count();
    $lowAttendanceCount = $this->getLowAttendanceCount();
    $batches        = Batch::where('status', 'active')->with('students')->get();
    $recentSessions = Session::with('subject.batch')->latest()->take(5)->get();
    $subjects       = Subject::with('batch')->get();

    $totalPresent = Attendance::where('status', 'Present')->count();
    $totalAbsent  = Attendance::where('status', 'Absent')->count();
    $totalLate    = Attendance::where('status', 'Late')->count();

    return view('admin.dashboard', compact(
        'totalStudents', 'totalBatches', 'totalSubjects',
        'totalLecturers', 'todaySessions', 'lowAttendanceCount',
        'batches', 'recentSessions', 'subjects',
        'totalPresent', 'totalAbsent', 'totalLate'
    ));
}

    public function alerts()
    {
        $subjects = Subject::with(['sessions', 'batch'])->get();
        $flagged = [];

        foreach ($subjects as $subject) {
            $totalSessions = $subject->sessions->count();
            if ($totalSessions === 0) continue;

            $students = Student::where('batch_id', $subject->batch_id)
                ->where('status', 'active')->get();

            foreach ($students as $student) {
                $present = Attendance::whereIn('session_id', $subject->sessions->pluck('id'))
                    ->where('student_id', $student->id)
                    ->whereIn('status', ['Present', 'Late'])
                    ->count();

                $percentage = round(($present / $totalSessions) * 100, 1);

                if ($percentage < 80) {
                    $flagged[] = [
                        'student' => $student,
                        'subject' => $subject,
                        'percentage' => $percentage,
                        'present' => $present,
                        'total' => $totalSessions,
                    ];
                }
            }
        }

        usort($flagged, fn($a, $b) => $a['percentage'] <=> $b['percentage']);

        return view('admin.alerts', compact('flagged'));
    }

    private function getLowAttendanceCount()
    {
        $subjects = Subject::with('sessions')->get();
        $count = 0;
        $seen = [];

        foreach ($subjects as $subject) {
            $totalSessions = $subject->sessions->count();
            if ($totalSessions === 0) continue;

            $students = Student::where('batch_id', $subject->batch_id)
                ->where('status', 'active')->get();

            foreach ($students as $student) {
                $key = $student->id . '_' . $subject->id;
                if (isset($seen[$key])) continue;

                $present = Attendance::whereIn('session_id', $subject->sessions->pluck('id'))
                    ->where('student_id', $student->id)
                    ->whereIn('status', ['Present', 'Late'])
                    ->count();

                $percentage = round(($present / $totalSessions) * 100, 1);
                if ($percentage < 80) {
                    $count++;
                    $seen[$key] = true;
                }
            }
        }

        return $count;
    }
}