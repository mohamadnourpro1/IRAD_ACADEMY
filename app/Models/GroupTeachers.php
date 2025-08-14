<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupTeachers extends Model
{
  protected $table = 'groups_teachers';
    use HasFactory;
    protected $fillable = [
        'group_id',
        'teacher_id',
    ];
}
