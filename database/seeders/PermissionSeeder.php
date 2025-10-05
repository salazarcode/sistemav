<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic permissions
        $permissions = [
            ['name' => 'create_user', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'edit_user', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete_user', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create_event', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'edit_event', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete_event', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'download_reports', 'created_at' => now(), 'updated_at' => now()],
        ];

        Permission::insert($permissions);
    }
}
