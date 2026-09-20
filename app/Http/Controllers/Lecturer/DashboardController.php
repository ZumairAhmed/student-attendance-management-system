<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('user_id', auth()->id())
            ->with('batch')
            ->get();

        $subjectData = $subjects->map(function ($subject) {
            $totalSessions = Session::where('subject_id', $subject->id)->count();
            $todaySession = Session::where('subject_id', $subject->id)
                ->whereDate('date', today())->first();
            return [
                'subject'       => $subject,
                'totalSessions' => $totalSessions,
                'todaySession'  => $todaySession,
            ];
        });

        return view('lecturer.dashboard', compact('subjectData'));
    }

    public function notifications()
{
    return view('lecturer.notifications');
}

}