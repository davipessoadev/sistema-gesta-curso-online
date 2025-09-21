<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'progress_percentage',
        'completion_date'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($enrollment) {
            if (!$enrollment->enrollment_date) {
                $enrollment->enrollment_date = now()->toDateString();
            }
        });
    }
}
