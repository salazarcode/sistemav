<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Añadir el campo birth_date
        Schema::table('personal_data', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('sex');
        });
        
        // Convertir datos existentes de age a birth_date si la columna age existe
        if (Schema::hasColumn('personal_data', 'age')) {
            $records = DB::table('personal_data')
                ->whereNotNull('age')
                ->get();
                
            foreach ($records as $record) {
                // Calcular fecha de nacimiento aproximada basada en edad
                $birthDate = Carbon::now()->subYears($record->age)->format('Y-m-d');
                
                // Actualizar el registro
                DB::table('personal_data')
                    ->where('id', $record->id)
                    ->update([
                        'birth_date' => $birthDate
                    ]);
            }
            
            // Después de crear el campo y convertir los datos, eliminamos la columna antigua
            Schema::table('personal_data', function (Blueprint $table) {
                $table->dropColumn('age');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primero añadimos de nuevo la columna age
        Schema::table('personal_data', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('sex');
        });
        
        // Convertir datos existentes si es posible
        if (Schema::hasColumn('personal_data', 'birth_date')) {
            $records = DB::table('personal_data')
                ->whereNotNull('birth_date')
                ->get();
                
            foreach ($records as $record) {
                $birthDate = Carbon::parse($record->birth_date);
                $age = $birthDate->age;
                
                DB::table('personal_data')
                    ->where('id', $record->id)
                    ->update([
                        'age' => $age
                    ]);
            }
            
            // Eliminamos la columna birth_date
            Schema::table('personal_data', function (Blueprint $table) {
                $table->dropColumn('birth_date');
            });
        }
    }
};
