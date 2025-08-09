<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Evitar duplicados usando firstOrCreate
        Institution::firstOrCreate(
            ['name' => 'Master Institution'],
            [
                'description' => 'Default institution for the Master user',
                'location' => 'Main Office',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Lista de instituciones adicionales
        $institutions = [
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
        
        // Crear instituciones sin duplicados
        foreach ($institutions as $institutionData) {
            Institution::firstOrCreate(
                ['name' => $institutionData['name']],
                [
                    'description' => $institutionData['description'],
                    'location' => $institutionData['location'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
