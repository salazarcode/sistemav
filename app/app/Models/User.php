<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'user_name',
        'password',
        'organizations_id',
        'parent_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
    ];

    protected $appends = ['all_supervised_users'];

    protected $with = ['supervisedUsers'];  // Eager load supervisedUsers by default

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'roles_id');
    }

    // Relación auto-referencial (un usuario puede tener un padre)
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relaciones con otras tablas
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizations_id');
    }

    // Método de compatibilidad para mantener el código existente funcionando
    public function institution()
    {
        return $this->organization();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permissions_id');
    }

    public function personalData()
    {
        return $this->belongsToMany(PersonalData::class, 'users_personal_data', 'user_id', 'personal_data_id')
                    ->withPivot('active')
                    ->withTimestamps();
    }

    // Agregar esta relación
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relación para obtener usuarios supervisados directos
    public function supervisedUsers()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
    
    /**
     * Verifica si el usuario actual es supervisor (directo o indirecto) del usuario dado
     *
     * @param User $user El usuario a verificar
     * @return bool True si el usuario actual es supervisor del usuario dado
     */
    public function isSupervisorOf(User $user): bool
    {
        // Si el usuario no tiene padre, no es supervisado por nadie
        if (!$user->parent_id) {
            return false;
        }
        
        // Si el usuario es supervisado directamente por el usuario actual
        if ($user->parent_id === $this->id) {
            return true;
        }
        
        // Verificar recursivamente si el padre del usuario es supervisado por el usuario actual
        $parent = $user->parent;
        return $parent ? $this->isSupervisorOf($parent) : false;
    }
    
    /**
     * Get all supervised users (direct and indirect)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSupervisedUsersAttribute()
    {
        try {
            // Obtener usuarios supervisados directos
            $directSupervisedUsers = $this->supervisedUsers;
            
            // Si no hay usuarios supervisados directos, devolver colección vacía
            if ($directSupervisedUsers->isEmpty()) {
                return collect();
            }
            
            // Inicializar colección con usuarios supervisados directos
            $allSupervisedUsers = collect();
            $processedIds = collect([$this->id]); // Evitar ciclos infinitos
            
            $usersToProcess = $directSupervisedUsers;
            
            while ($usersToProcess->isNotEmpty()) {
                $currentUser = $usersToProcess->shift();
                
                if (!$processedIds->contains($currentUser->id)) {
                    $allSupervisedUsers->push($currentUser);
                    $processedIds->push($currentUser->id);
                    
                    // Agregar los supervisados directos del usuario actual a la cola
                    $currentUserSupervisedUsers = $currentUser->supervisedUsers;
                    $usersToProcess = $usersToProcess->merge($currentUserSupervisedUsers);
                }
            }
            
            return $allSupervisedUsers;
        } catch (\Exception $e) {
            \Log::error('Error getting supervised users: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Verifica si el usuario tiene un rol específico
     *
     * @param string $roleName Nombre del rol a verificar
     * @return bool True si el usuario tiene el rol especificado
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Get the plain password record associated with the user.
     */
    public function plainPassword()
    {
        return $this->hasOne(PlainPassword::class);
    }
} 