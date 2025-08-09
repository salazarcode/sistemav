<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('organizations_id')->after('end_date')->constrained();
            $table->foreignId('user_id')->after('organizations_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['organizations_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['organizations_id', 'user_id']);
        });
    }
}; 