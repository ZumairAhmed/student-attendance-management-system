<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['full_name', 'index_no', 'batch_id', 'email', 'phone', 'status'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAttendancePercentage($subjectId)
    {
        $totalSessions = Session::where('subject_id', $subjectId)->count();
        if ($totalSessions === 0) return 100;
        $present = Attendance::where('student_id', $this->id)
            ->whereIn('session_id', Session::where('subject_id', $subjectId)->pluck('id'))
            ->whereIn('status', ['Present', 'Late'])
            ->count();
        return round(($present / $totalSessions) * 100, 1);
    }
}