<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Musical' => 'Eventos de música y conciertos',
            'Teatral' => 'Obras de teatro y espectáculos',
            'Político' => 'Eventos políticos y campañas',
            'Deportivo' => 'Eventos deportivos y competencias',
            'Educativo' => 'Conferencias y talleres educativos',
            'Cultural' => 'Eventos culturales y exposiciones',
            'Empresarial' => 'Eventos corporativos y networking',
            'Social' => 'Eventos sociales y comunitarios',
            'Religioso' => 'Eventos religiosos y ceremonias',
            'Tecnológico' => 'Eventos de tecnología e innovación',
            'Elecciones' => 'Procesos electorales',
        ];
        
        // Crear categorías sin duplicados
        foreach ($categories as $name => $description) {
            Category::firstOrCreate(
                ['description' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
