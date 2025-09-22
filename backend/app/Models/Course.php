<?php

namespace App\Models;

use App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration_hours'
    ];


    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')->withPivot('id', 'progress_percentage', 'enrollment_date');
    }
}
