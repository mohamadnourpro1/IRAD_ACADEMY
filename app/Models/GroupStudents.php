<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupStudents extends Model
{
  protected $table = 'groups_students';
    use HasFactory;
    protected $fillable = [
        'group_id',
        'student_id',
    ];
}
