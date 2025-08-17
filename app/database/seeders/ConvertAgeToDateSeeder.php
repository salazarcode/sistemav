<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonalData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConvertAgeToDateSeeder extends Seeder
{
    /**
     * Convert age to birth_date for existing records.
     */
    public function run(): void
    {
        // Seleccionar registros con age pero sin birth_date
        $data = DB::table('personal_data')
            ->whereNotNull('age')
            ->whereNull('birth_date')
            ->get();
            
        $this->command->info('Converting age to birth_date for ' . $data->count() . ' records...');
        
        foreach ($data as $record) {
            // Calcular fecha de nacimiento aproximada basada en edad
            // Asumimos que la persona justo cumplió años
            $birthDate = Carbon::now()->subYears($record->age)->format('Y-m-d');
            
            // Actualizar el registro
            DB::table('personal_data')
                ->where('id', $record->id)
                ->update([
                    'birth_date' => $birthDate
                ]);
        }
        
        $this->command->info('Conversion completed successfully!');
    }
}
