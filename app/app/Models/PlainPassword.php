<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlainPassword extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plain_password',
    ];

    /**
     * Get the user that owns the plain password.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
