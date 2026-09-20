<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'batch_id', 'user_id', 'semester', 'is_locked'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
