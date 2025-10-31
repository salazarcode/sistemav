<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            // Agrega una restricción única para la combinación de dni y type_dni
            $table->unique(['dni', 'type_dni']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            // Elimina la restricción única
            $table->dropUnique(['dni', 'type_dni']);
        });
    }
};
