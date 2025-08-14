<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $table = 'groups';
    use HasFactory;
    protected $fillable = ['name', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'groups_students', 'group_id', 'student_id');
    }
        public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'groups_teachers', 'group_id', 'teacher_id');
    }


}
