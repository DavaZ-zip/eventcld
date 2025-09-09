<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'description',
        'website',
        'contact_person',
        'contact_email',
        'contact_phone',
        'contribution_amount',
        'sponsorship_level',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_sponsors')
                    ->withPivot('sponsorship_level', 'contribution_amount')
                    ->withTimestamps();
    }
}
