<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إنشاء دور Admin إذا لم يكن موجوداً
        $role = Role::firstOrCreate(['name' => 'Admin']);
        
        // إنشاء المستخدم admin
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('ir12345678'),
            ]
        );
        
        // تعيين الدور للمستخدم
        $user->assignRole($role);
        
        $this->command->info('Admin user created successfully with email: admin@gmail.com and password: ir12345678');
    }
}
