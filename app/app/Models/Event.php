<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'img',
        'start_date',
        'end_date',
        'organizations_id',
        'user_id',
        'slug',
        'qr_code'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizations_id');
    }

    // Método de compatibilidad
    public function institution()
    {
        return $this->organization();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_category');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
} 