<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'events_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'speakers_id',
        'location',
        'max_participants',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'session_participants')
                    ->withPivot('attendance_status')
                    ->withTimestamps();
    }
}
