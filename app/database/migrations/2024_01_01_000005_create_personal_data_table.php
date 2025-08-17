<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('address');
            $table->string('sex');
            $table->integer('age');
            $table->string('dni');
            $table->string('type_dni');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_data');
    }
}; 