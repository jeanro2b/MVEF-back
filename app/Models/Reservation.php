<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'text',
        'destination_id',
        'start',
        'end',
        'status',
        'amount',
        'intent',
        'name',
        'first_name',
        'phone',
        'mail',
        'hebergement_id',
        'user_id'
    ];

    public function destination()
    {
        return $this->belongsToMany(Destination::class);
    }

    public function hebergement()
    {
        return $this->belongsToMany(Hebergement::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
