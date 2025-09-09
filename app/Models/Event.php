<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'max_participants',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'event_participants')
                    ->withPivot('registration_date', 'status', 'notes')
                    ->withTimestamps();
    }

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'event_sponsors')
                    ->withPivot('sponsorship_level', 'contribution_amount')
                    ->withTimestamps();
    }

    public function getRegisteredCountAttribute()
    {
        return $this->participants()->count();
    }
}
