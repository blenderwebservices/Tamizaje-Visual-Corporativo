<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'contact_phone',
        'contact_email',
        'location',
        'event_date',
        'notes',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }
}
