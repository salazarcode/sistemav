<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'event_id',
        'attendance',
        'dni',
        'age',
        'gender',
        'institution',
        'profession',
        'education_level',
        'ticket_type',
        'seat_number',
        'team',
        'category',
        'participant_type',
        'personal_data_id',
        'assists_id'
    ];

    protected $casts = [
        'attendance' => 'boolean',
        'age' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class);
    }

    public function assist()
    {
        return $this->belongsTo(Assist::class, 'assists_id');
    }
} 