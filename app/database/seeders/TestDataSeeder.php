<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Organization;
use App\Models\Participant;
use App\Models\PersonalData;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        
        // No necesitamos crear categorías aquí, ya son manejadas por CategorySeeder
        // Las obtenemos para usarlas
        $allCategories = Category::all();
        
        // No necesitamos crear organizaciones aquí, ya son manejadas por OrganizationSeeder
        // Las obtenemos para usarlas
        $allOrganizations = Organization::all();
        
        // Create supervisors (users with parent_id = 1, which is the Master user)
        $this->command->info('Creating supervisors...');
        $supervisors = [];
        
        // Get roles
        $supervisorRoleId = 2; // Assuming Supervisor role has ID 2
        $userRoleId = 3; // Assuming User role has ID 3
        
        // Get permissions
        $createUserPermissionId = 1; // create_user
        $editUserPermissionId = 2; // edit_user
        $deleteUserPermissionId = 3; // delete_user
        $createEventPermissionId = 4; // create_event
        $editEventPermissionId = 5; // edit_event
        $deleteEventPermissionId = 6; // delete_event
        $manageParticipantsPermissionId = 7; // manage_participants
        
        // Check if we already have test supervisors
        $existingSupervisors = User::where('email', 'like', 'test_supervisor%@example.com')->get();
        if ($existingSupervisors->count() > 0) {
            $this->command->info('Using existing supervisors...');
            $supervisors = $existingSupervisors->all();
        } else {
            for ($i = 0; $i < 10; $i++) {
                $supervisor = User::create([
                    'user_name' => 'test_supervisor' . ($i + 1),
                    'email' => 'test_supervisor' . ($i + 1) . '@example.com',
                    'password' => Hash::make('password'),
                    'organizations_id' => $allOrganizations->random()->id,
                    'parent_id' => 1, // Master user
                    'preferences' => ['dashboard_view' => 'list']
                ]);
                
                // Assign role to supervisor
                \DB::table('users_roles')->insert([
                    'user_id' => $supervisor->id,
                    'roles_id' => $supervisorRoleId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Assign permissions to supervisor
                $permissions = [
                    $createUserPermissionId,
                    $editUserPermissionId,
                    $deleteUserPermissionId,
                    $createEventPermissionId,
                    $editEventPermissionId,
                    $deleteEventPermissionId,
                    $manageParticipantsPermissionId
                ];
                
                foreach ($permissions as $permissionId) {
                    \DB::table('user_permissions')->insert([
                        'user_id' => $supervisor->id,
                        'permissions_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                // Create personal data for supervisor
                $personalData = PersonalData::create([
                    'name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                    'dni' => 'V' . $faker->numberBetween(10000000, 30000000),
                    'type_dni' => 'V',
                    'sex' => $faker->randomElement(['M', 'F']),
                    'age' => $faker->numberBetween(25, 60)
                ]);
                
                // Link personal data to supervisor
                \DB::table('users_personal_data')->insert([
                    'user_id' => $supervisor->id,
                    'personal_data_id' => $personalData->id,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $supervisors[] = $supervisor;
            }
        }
        
        // Create supervised users (30 per supervisor)
        $this->command->info('Creating supervised users...');
        $supervisedUsers = [];
        
        // Check if we already have test supervised users
        $existingSupervisedUsers = User::where('email', 'like', 'test_user_%@example.com')->get();
        if ($existingSupervisedUsers->count() > 0) {
            $this->command->info('Using existing supervised users...');
            $supervisedUsers = $existingSupervisedUsers->all();
        } else {
            foreach ($supervisors as $supervisor) {
                for ($i = 0; $i < 30; $i++) {
                    $supervisedUser = User::create([
                        'user_name' => 'test_user_' . $supervisor->id . '_' . ($i + 1),
                        'email' => 'test_user_' . $supervisor->id . '_' . ($i + 1) . '@example.com',
                        'password' => Hash::make('password'),
                        'organizations_id' => $allOrganizations->random()->id,
                        'parent_id' => $supervisor->id,
                        'preferences' => ['dashboard_view' => 'list']
                    ]);
                    
                    // Assign role to supervised user
                    \DB::table('users_roles')->insert([
                        'user_id' => $supervisedUser->id,
                        'roles_id' => $userRoleId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    // Assign permissions to supervised user
                    $permissions = [
                        $createEventPermissionId,
                        $editEventPermissionId,
                        $manageParticipantsPermissionId
                    ];
                    
                    foreach ($permissions as $permissionId) {
                        \DB::table('user_permissions')->insert([
                            'user_id' => $supervisedUser->id,
                            'permissions_id' => $permissionId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    
                    // Create personal data for supervised user
                    $personalData = PersonalData::create([
                        'name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address,
                        'dni' => 'V' . $faker->numberBetween(10000000, 30000000),
                        'type_dni' => 'V',
                        'sex' => $faker->randomElement(['M', 'F']),
                        'age' => $faker->numberBetween(18, 60)
                    ]);
                    
                    // Link personal data to supervised user
                    \DB::table('users_personal_data')->insert([
                        'user_id' => $supervisedUser->id,
                        'personal_data_id' => $personalData->id,
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $supervisedUsers[] = $supervisedUser;
                }
            }
        }
        
        // Create events (5-15 per supervised user)
        $this->command->info('Creating events...');
        $events = [];
        
        // Check if we already have test events
        $existingEvents = Event::where('name', 'like', 'Test Event%')->get();
        if ($existingEvents->count() > 0) {
            $this->command->info('Using existing events...');
            $events = $existingEvents->all();
        } else {
            foreach ($supervisedUsers as $user) {
                $numEvents = $faker->numberBetween(5, 15);
                
                for ($i = 0; $i < $numEvents; $i++) {
                    $startDate = $faker->dateTimeBetween('-1 year', '+6 months');
                    $endDate = (clone $startDate)->modify('+' . $faker->numberBetween(1, 7) . ' days');
                    
                    $eventName = 'Test Event ' . $user->id . '-' . ($i + 1);
                    $slug = Str::slug($eventName . '-' . Str::random(5));
                    
                    $event = Event::create([
                        'name' => $eventName,
                        'description' => $faker->paragraph,
                        'location' => $faker->address,
                        'img' => 'https://picsum.photos/800/600?random=' . $faker->numberBetween(1, 1000),
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'organizations_id' => $user->organizations_id,
                        'user_id' => $user->id,
                        'slug' => $slug,
                        'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . route('events.public.show', $slug)
                    ]);
                    
                    // Assign 1-3 random categories to the event
                    $categoryCount = $faker->numberBetween(1, 3);
                    $eventCategories = $allCategories->random($categoryCount);
                    
                    foreach ($eventCategories as $category) {
                        $event->categories()->attach($category->id);
                    }
                    
                    $events[] = $event;
                }
            }
        }
        
        // Create participants for each event (10-100 per event)
        $this->command->info('Creating participants...');
        
        // Check if we already have participants
        $existingParticipantsCount = Participant::count();
        if ($existingParticipantsCount > 1000) {
            $this->command->info('Using existing participants...');
        } else {
            foreach ($events as $event) {
                $numParticipants = $faker->numberBetween(10, 100);
                $eventCategories = $event->categories->pluck('description')->toArray();
                
                for ($i = 0; $i < $numParticipants; $i++) {
                    // Crear datos personales para el participante
                    $personalData = PersonalData::create([
                        'name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address,
                        'dni' => 'V' . $faker->numberBetween(10000000, 30000000),
                        'type_dni' => 'V',
                        'sex' => $faker->randomElement(['M', 'F', 'O']),
                        'age' => $faker->numberBetween(18, 80)
                    ]);
                    
                    $participantData = [
                        'name' => $personalData->name,
                        'last_name' => $personalData->last_name,
                        'email' => $faker->email,
                        'phone' => $personalData->phone,
                        'event_id' => $event->id,
                        'attendance' => $faker->boolean(70), // 70% chance of attendance
                        'created_at' => $faker->dateTimeBetween($event->start_date, $event->end_date),
                        'personal_data_id' => $personalData->id,
                        'gender' => $personalData->sex
                    ];
                    
                    // Add specific fields based on event category
                    if (in_array('Político', $eventCategories) || in_array('Elecciones', $eventCategories)) {
                        $participantData['dni'] = $personalData->dni;
                        $participantData['address'] = $personalData->address;
                        $participantData['age'] = $personalData->age;
                    }
                    
                    if (in_array('Educativo', $eventCategories)) {
                        $participantData['institution'] = $faker->company;
                        $participantData['profession'] = $faker->jobTitle;
                        $participantData['education_level'] = $faker->randomElement(['Primaria', 'Secundaria', 'Universitaria', 'Postgrado']);
                    }
                    
                    if (in_array('Musical', $eventCategories) || in_array('Teatral', $eventCategories) || in_array('Cultural', $eventCategories)) {
                        $participantData['ticket_type'] = $faker->randomElement(['General', 'VIP', 'Platinum']);
                        $participantData['seat_number'] = $faker->bothify('?-###');
                    }
                    
                    if (in_array('Deportivo', $eventCategories)) {
                        $participantData['team'] = $faker->company;
                        $participantData['category'] = $faker->randomElement(['Junior', 'Senior', 'Master']);
                        $participantData['participant_type'] = $faker->randomElement(['Atleta', 'Entrenador', 'Juez', 'Espectador']);
                    }
                    
                    // Create the participant with the data
                    Participant::create($participantData);
                }
            }
        }
        
        $this->command->info('Test data seeded successfully!');
    }
} 