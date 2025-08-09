<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitializeProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:initialize {--fresh : If set, will run migrate:fresh instead of migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the project with basic data (roles, permissions, organizations, categories)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting project initialization...');
        
        // Step 1: Run migrations
        $this->info('Running migrations...');
        if ($this->option('fresh')) {
            $this->info('Dropping all tables and migrating from scratch...');
            Artisan::call('migrate:fresh');
            $this->info('Database migrated from scratch.');
        } else {
            Artisan::call('migrate');
            $this->info('Database migrated.');
        }
        
        // Step 2: Run basic seeders
        $this->info('Seeding basic data...');
        
        // Roles
        $this->info('Creating roles...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\RoleSeeder',
            '--force' => true
        ]);
        $this->info('Roles created successfully.');
        
        // Permissions
        $this->info('Creating permissions...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\PermissionSeeder',
            '--force' => true
        ]);
        $this->info('Permissions created successfully.');
        
        // Organizations
        $this->info('Creating initial organizations...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\InitialOrganizationSeeder',
            '--force' => true
        ]);
        $this->info('Organizations created successfully.');
        
        // Categories
        $this->info('Creating categories...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\CategorySeeder',
            '--force' => true
        ]);
        $this->info('Categories created successfully.');
        
        $this->info('Project initialization completed successfully!');
        $this->info('You can now register the first admin user through the application.');
        
        return Command::SUCCESS;
    }
} 