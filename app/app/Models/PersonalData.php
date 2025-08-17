<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PersonalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'address',
        'sex',
        'birth_date',
        'dni',
        'type_dni',
        'email'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Calcular la edad a partir de la fecha de nacimiento
     * 
     * @return int|null
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_personal_data', 'personal_data_id', 'user_id')
                    ->withPivot('active')
                    ->withTimestamps();
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
} 