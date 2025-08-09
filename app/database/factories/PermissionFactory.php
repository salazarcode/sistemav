<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'create_event',
                'edit_event',
                'delete_event',
                'view_event',
                'manage_users',
                'manage_roles',
                'manage_permissions',
                'manage_institutions',
                'view_reports',
                'export_data',
                'manage_categories',
                'manage_participants',
                'view_dashboard',
                'edit_profile'
            ])
        ];
    }
} 