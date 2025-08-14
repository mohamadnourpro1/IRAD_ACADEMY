<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
      protected $fillable =
     [
      'user_id',
      'name',
      'email',
      'phone',
      'address',
      'password',
      'profile_image',
    ];
    protected static function boot()
    {
        parent::boot();
        static::created(function ($teacher) {
            $request = request();
            if ($request->has(['name', 'email', 'password'])) {
                $user = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]);
                $role = \Spatie\Permission\Models\Role::where('name','Teacher')->first();
                $user->assignRole($role);
                $teacher->user_id = $user->id;
                $teacher->save();
            }
        });
        static::updated(function ($teacher) {
            $request = request();
            $user = $teacher->user;
            if ($user) {
                $user->update([
                    'email' => $request->email ?? $user->email,
                    'password' => $request->password ? \Illuminate\Support\Facades\Hash::make($request->password) : $user->password,
                ]);
            }
        });
        static::deleting(function ($teacher) {
            $user = $teacher->user;
            if ($user) {
                $user->delete();
            }
        });
    }

    public function user(){
      return  $this->belongsTo(User::class,'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_teachers', 'teacher_id', 'group_id');
    }
}
