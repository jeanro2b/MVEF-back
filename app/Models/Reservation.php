<?php

namespace App\Models;

use Carbon\Carbon;
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
        'user_id',
        'token',
        'services',
        'comment',
        'amount_options',
        'amount_nights',
        'is_checked',
        'reduction',
        'voyageurs',
        'code',
        'acceptation',
        'payment_method',
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

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($reservation) {
            // Vérifier si la date 'end' est dans le passé
            if (Carbon::parse($reservation->end)->isPast()) {
                $reservation->status = 'Terminé'; // Modifier le statut de la réservation
                $reservation->save(); // Sauvegarder les modifications
            }
        });
    }
}
