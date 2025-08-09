<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the new table first if it doesn't exist
        if (!Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->string('location');
                $table->timestamps();
            });
        }

        // 2. Copy data from institutions to organizations
        if (Schema::hasTable('institutions') && Schema::hasTable('organizations')) {
            $institutions = DB::table('institutions')->get();
            foreach ($institutions as $institution) {
                DB::table('organizations')->updateOrInsert(
                    ['id' => $institution->id],
                    [
                        'name' => $institution->name,
                        'description' => $institution->description,
                        'location' => $institution->location,
                        'created_at' => $institution->created_at,
                        'updated_at' => $institution->updated_at
                    ]
                );
            }
        }

        // 3. Update foreign keys in users table
        if (Schema::hasColumn('users', 'institutions_id') && !Schema::hasColumn('users', 'organizations_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('organizations_id')->after('institutions_id')->nullable();
            });

            // Copy the values
            DB::statement('UPDATE users SET organizations_id = institutions_id');
            
            // Drop the old column
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['institutions_id']);
                $table->dropColumn('institutions_id');
            });
        }

        // 4. Update foreign keys in events table
        if (Schema::hasColumn('events', 'institutions_id') && !Schema::hasColumn('events', 'organizations_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('organizations_id')->after('institutions_id')->nullable();
            });

            // Copy the values
            DB::statement('UPDATE events SET organizations_id = institutions_id');
            
            // Drop the old column
            Schema::table('events', function (Blueprint $table) {
                $table->dropForeign(['institutions_id']);
                $table->dropColumn('institutions_id');
            });
        }

        // 5. Drop the old institutions table if needed
        if (Schema::hasTable('institutions')) {
            Schema::dropIfExists('institutions');
        }
    }

    public function down(): void
    {
        // 1. Create the institutions table again
        if (!Schema::hasTable('institutions')) {
            Schema::create('institutions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->string('location');
                $table->timestamps();
            });
        }

        // 2. Copy data back from organizations to institutions
        if (Schema::hasTable('institutions') && Schema::hasTable('organizations')) {
            $organizations = DB::table('organizations')->get();
            foreach ($organizations as $organization) {
                DB::table('institutions')->updateOrInsert(
                    ['id' => $organization->id],
                    [
                        'name' => $organization->name,
                        'description' => $organization->description,
                        'location' => $organization->location,
                        'created_at' => $organization->created_at,
                        'updated_at' => $organization->updated_at
                    ]
                );
            }
        }

        // 3. Update foreign keys in users table
        if (Schema::hasColumn('users', 'organizations_id') && !Schema::hasColumn('users', 'institutions_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('institutions_id')->after('organizations_id')->nullable();
            });

            // Copy the values
            DB::statement('UPDATE users SET institutions_id = organizations_id');
            
            // Drop the new column
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['organizations_id']);
                $table->dropColumn('organizations_id');
            });
        }

        // 4. Update foreign keys in events table
        if (Schema::hasColumn('events', 'organizations_id') && !Schema::hasColumn('events', 'institutions_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('institutions_id')->after('organizations_id')->nullable();
            });

            // Copy the values
            DB::statement('UPDATE events SET institutions_id = organizations_id');
            
            // Drop the new column
            Schema::table('events', function (Blueprint $table) {
                $table->dropForeign(['organizations_id']);
                $table->dropColumn('organizations_id');
            });
        }

        // 5. Drop the organizations table
        Schema::dropIfExists('organizations');
    }
}; 