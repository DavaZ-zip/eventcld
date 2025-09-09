<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'email',
        'phone',
        'photo',
        'expertise',
        'social_media',
    ];

    protected function casts(): array
    {
        return [
            'social_media' => 'array',
        ];
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }
}
