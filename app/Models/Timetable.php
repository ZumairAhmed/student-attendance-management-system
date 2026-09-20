<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        'batch_id', 'semester', 'academic_year',
        'effective_date', 'days', 'status'
    ];

    protected $casts = [
        'days' => 'array',
        'effective_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function slots()
    {
        return $this->hasMany(TimetableSlot::class);
    }

    public function subjects()
    {
        return $this->slots()
            ->where('is_continuation', false)
            ->with('subject.lecturer')
            ->get()
            ->pluck('subject')
            ->unique('id');
    }
}