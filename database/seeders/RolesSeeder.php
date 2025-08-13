<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('roles')->delete();
      $roles = [
        [
          'name' => 'Admin',
          'guard_name' => 'api'
        ],
        [
          'name' => 'Staff',
          'guard_name' => 'api'
        ],
        [
          'name' => 'Teacher',
          'guard_name' => 'api'
        ],
        [
          'name' => 'Student',
          'guard_name' => 'api'
        ],
      ];
        Role::insert($roles);
    }
    
}
