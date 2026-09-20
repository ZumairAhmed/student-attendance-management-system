<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableSlot extends Model
{
    protected $fillable = [
        'timetable_id', 'subject_id', 'day',
        'start_time', 'end_time', 'is_continuation', 'slot_group'
    ];

    protected $casts = [
        'is_continuation' => 'boolean',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}