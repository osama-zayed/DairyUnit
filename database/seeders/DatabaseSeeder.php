<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء الأدوار
        $institution = Role::create(['name' => 'institution']);
        $association = Role::create(['name' => 'association']);
        $representative = Role::create(['name' => 'representative']);
        $collector = Role::create(['name' => 'collector']);
        
        // // إنشاء الصلاحيات
        // Permission::create(['name' => 'manage deliveries']);
        // Permission::create(['name' => 'view reports']);
        
        // // ربط الصلاحيات بالأدوار
        // $institution->givePermissionTo('manage deliveries');
        // $association->givePermissionTo('view reports');

        $user = User::create([
            'name' => 'وحدة الالبان',
            'phone' => '777888999',
            'password' => bcrypt('123123123'),
        ]);
        
        $role = Role::where('name', 'institution')->first();
        
        $user->syncRoles([$role->id]);
        
    }
}
