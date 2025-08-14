<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourses extends Model
{
  protected $table = 'category_courses';
    use HasFactory;
    protected $fillable = [
      'name',#example web
      'level',#example basic
    ];
    public function courses()
    {
        return $this->hasMany(Course::class,'category_id');
    }
}
