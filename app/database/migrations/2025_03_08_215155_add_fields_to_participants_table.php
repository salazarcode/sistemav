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
        Schema::table('participants', function (Blueprint $table) {
            // Make personal_data_id nullable
            $table->unsignedBigInteger('personal_data_id')->nullable()->change();
            
            // Campos básicos para todos los participantes
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('attendance')->default(false);
            
            // Campos para eventos políticos y elecciones
            $table->string('dni')->nullable();
            $table->text('address')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            
            // Campos para eventos educativos
            $table->string('institution')->nullable();
            $table->string('profession')->nullable();
            $table->string('education_level')->nullable();
            
            // Campos para eventos culturales, musicales y teatrales
            $table->string('ticket_type')->nullable();
            $table->string('seat_number')->nullable();
            
            // Campos para eventos deportivos
            $table->string('team')->nullable();
            $table->string('category')->nullable();
            $table->string('participant_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'last_name',
                'email',
                'phone',
                'attendance',
                'dni',
                'address',
                'age',
                'gender',
                'institution',
                'profession',
                'education_level',
                'ticket_type',
                'seat_number',
                'team',
                'category',
                'participant_type',
            ]);
        });
    }
};
