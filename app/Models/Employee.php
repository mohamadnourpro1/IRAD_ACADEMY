<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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
        static::created(function ($employee) {
            $request = request();
            if ($request->has(['name', 'email', 'password'])) {
                $user = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]);
                $role = \Spatie\Permission\Models\Role::where('name','Staff')->first();
                $user->assignRole($role);
                $employee->user_id = $user->id;
                $employee->save();
            }
        });
        static::updated(function ($employee) {
            $request = request();
            $user = $employee->user;
            if ($user) {
                $user->update([
                    'email' => $request->email ?? $user->email,
                    'password' => $request->password ? \Illuminate\Support\Facades\Hash::make($request->password) : $user->password,
                ]);
            }
        });
        static::deleting(function ($employee) {
            $user = $employee->user;
            if ($user) {
                $user->delete();
            }
        });
    }
    public function user(){
       return$this->belongsTo(User::class);
    }
}
