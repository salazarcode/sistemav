<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organizations_id')->after('password')->constrained();
            $table->foreignId('parent_id')->nullable()->after('organizations_id')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organizations_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['organizations_id', 'parent_id']);
        });
    }
}; 