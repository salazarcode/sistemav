<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedInitialOrganizationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-initial-organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with initial organizations only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding initial organizations...');
        
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\InitialOrganizationSeeder',
                '--force' => true
            ]);
            
            $this->info('Initial organizations seeded successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error seeding initial organizations: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 