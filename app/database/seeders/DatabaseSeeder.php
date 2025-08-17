<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seeders básicos
            RoleSeeder::class,
            PermissionSeeder::class,
            
            // Para usar solo organizaciones iniciales básicas, descomentar:
            // InitialOrganizationSeeder::class,
            
            // O para usar el conjunto completo de organizaciones, descomentar:
            OrganizationSeeder::class, // Actualizado de InstitutionSeeder
            
            CategorySeeder::class,    // Seeder optimizado que incluye todas las categorías
            MasterUserSeeder::class,
            
            // Descomentar para generar datos de prueba adicionales
            TestDataSeeder::class,
        ]);
    }
}
