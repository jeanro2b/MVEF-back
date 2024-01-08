<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'end'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
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
