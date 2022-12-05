<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model
{
    protected $fillable = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
