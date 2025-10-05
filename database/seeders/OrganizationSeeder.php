<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Evitar duplicados usando firstOrCreate
        Organization::firstOrCreate(
            ['name' => 'Master Organization'],
            [
                'description' => 'Default organization for the Master user',
                'location' => 'Main Office',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Lista de organizaciones adicionales
        $organizations = [
            [
                'name' => 'Universidad Central',
                'description' => 'Universidad Central de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Ministerio de Educación',
                'description' => 'Ministerio de Educación de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Hospital Universitario',
                'description' => 'Hospital Universitario de Caracas',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Alcaldía de Caracas',
                'description' => 'Alcaldía del Municipio Libertador',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Fundación Cultural',
                'description' => 'Fundación para el desarrollo cultural',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Alcaldía Metropolitana',
                'description' => 'Gobierno local de la ciudad',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Teatro Nacional',
                'description' => 'Teatro Nacional de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Asociación Deportiva',
                'description' => 'Asociación deportiva local',
                'location' => 'Caracas, Venezuela',
            ],
        ];
        
        // Crear organizaciones sin duplicados
        foreach ($organizations as $organizationData) {
            Organization::firstOrCreate(
                ['name' => $organizationData['name']],
                [
                    'description' => $organizationData['description'],
                    'location' => $organizationData['location'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
} 