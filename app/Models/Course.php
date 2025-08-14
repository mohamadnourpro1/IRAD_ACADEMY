<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  protected $table = 'courses';
    use HasFactory;
    protected $fillable = ['name','description','category_id'];
    public function category()
    {
        return $this->belongsTo(CategoryCourses::class, 'category_id');
    }
    public function groups()
    {
        return $this->hasMany(Group::class,'course_id');
    }
}
