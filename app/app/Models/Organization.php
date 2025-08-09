<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'organizations_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizations_id');
    }
} 