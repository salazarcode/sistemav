<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class InitialOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder contains only the initial basic organizations
     * to setup the project in a clean state.
     */
    public function run(): void
    {
        // Master Organization - Required for admin users
        Organization::firstOrCreate(
            ['name' => 'Master Organization'],
            [
                'description' => 'Default organization for the Master user',
                'location' => 'Main Office',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Basic initial organizations list
        $organizations = [
            // Instituciones Educativas
            [
                'name' => 'Universidad Central',
                'description' => 'Universidad Central de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Universidad Simón Bolívar',
                'description' => 'Universidad Simón Bolívar',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Universidad de Los Andes',
                'description' => 'Universidad de Los Andes',
                'location' => 'Mérida, Venezuela',
            ],
            
            // Organismos Gubernamentales
            [
                'name' => 'Ministerio de Educación',
                'description' => 'Ministerio de Educación de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Ministerio de Salud',
                'description' => 'Ministerio del Poder Popular para la Salud',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'Ministerio de Ciencia y Tecnología',
                'description' => 'Ministerio del Poder Popular para Ciencia y Tecnología',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'PDVSA',
                'description' => 'Petróleos de Venezuela S.A.',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'CANTV',
                'description' => 'Compañía Anónima Nacional Teléfonos de Venezuela',
                'location' => 'Caracas, Venezuela',
            ],
            
            // Gobernaciones de Estados
            [
                'name' => 'Gobernación de Miranda',
                'description' => 'Gobierno del Estado Miranda',
                'location' => 'Los Teques, Venezuela',
            ],
            [
                'name' => 'Gobernación de Zulia',
                'description' => 'Gobierno del Estado Zulia',
                'location' => 'Maracaibo, Venezuela',
            ],
            [
                'name' => 'Gobernación de Carabobo',
                'description' => 'Gobierno del Estado Carabobo',
                'location' => 'Valencia, Venezuela',
            ],
            [
                'name' => 'Gobernación de Aragua',
                'description' => 'Gobierno del Estado Aragua',
                'location' => 'Maracay, Venezuela',
            ],
            [
                'name' => 'Gobernación de Lara',
                'description' => 'Gobierno del Estado Lara',
                'location' => 'Barquisimeto, Venezuela',
            ],
            
            // Instituciones de Salud
            [
                'name' => 'Hospital Universitario',
                'description' => 'Hospital Universitario de Caracas',
                'location' => 'Caracas, Venezuela',
            ],
            [
                'name' => 'IVSS',
                'description' => 'Instituto Venezolano de los Seguros Sociales',
                'location' => 'Caracas, Venezuela',
            ],
        ];
        
        // Create organizations without duplicates
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