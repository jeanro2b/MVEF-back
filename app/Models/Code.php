<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'end',
        'reduction',
        'destination_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function destination()
    {
        return $this->hasOne(Destination::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($code) {
            // Vérifier si la date 'end' est dans le passé
            if (Carbon::parse($code->end)->isPast()) {
                $code->delete(); // Supprimer l'entrée si la date est passée
            }
        });
    }
}
