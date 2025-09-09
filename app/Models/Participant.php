<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization',
        'position',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
                    ->withPivot('registration_date', 'status', 'notes')
                    ->withTimestamps();
    }

    public function sessions()
    {
        return $this->belongsToMany(EventSession::class, 'session_participants')
                    ->withPivot('attendance_status')
                    ->withTimestamps();
    }
}
