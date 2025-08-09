<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PlainPassword;
use Illuminate\Support\Facades\Hash;

class MigrateExistingPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-existing-passwords {default_password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra las contraseñas existentes a la tabla plain_passwords con una contraseña por defecto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = $this->argument('default_password');
        
        $this->info('Migrando contraseñas existentes...');
        
        // Obtener todos los usuarios que no tienen una contraseña en texto plano
        $users = User::whereDoesntHave('plainPassword')->get();
        
        $this->info("Se encontraron {$users->count()} usuarios sin contraseña en texto plano.");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        foreach ($users as $user) {
            // Crear una entrada en la tabla plain_passwords con la contraseña por defecto
            PlainPassword::create([
                'user_id' => $user->id,
                'plain_password' => $defaultPassword,
            ]);
            
            // Actualizar la contraseña del usuario con el hash de la contraseña por defecto
            $user->update([
                'password' => Hash::make($defaultPassword),
            ]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info('Migración completada. Todos los usuarios tienen ahora la contraseña por defecto: ' . $defaultPassword);
        $this->info('Se recomienda cambiar las contraseñas de los usuarios después de esta migración.');
    }
}
