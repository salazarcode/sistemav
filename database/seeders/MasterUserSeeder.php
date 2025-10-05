<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\PersonalData;
use App\Models\Permission;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Master role
        $masterRole = Role::where('name', 'Master')->first();
        
        // Get the default organization
        $organization = Organization::first();

        // Create the Master user
        $user = User::create([
            'email' => 'master@example.com',
            'user_name' => 'master',
            'password' => Hash::make('password'),
            'organizations_id' => $organization->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create personal data for the Master user
        $personalData = PersonalData::create([
            'name' => 'Master',
            'last_name' => 'Admin',
            'phone' => '1234567890',
            'address' => 'Main Street 123',
            'sex' => 'M',
            'age' => 30,
            'dni' => '12345678',
            'type_dni' => 'ID',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Link personal data to user
        DB::table('users_personal_data')->insert([
            'user_id' => $user->id,
            'personal_data_id' => $personalData->id,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Assign Master role to user
        DB::table('users_roles')->insert([
            'user_id' => $user->id,
            'roles_id' => $masterRole->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Assign all permissions to Master user
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            DB::table('user_permissions')->insert([
                'user_id' => $user->id,
                'permissions_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
