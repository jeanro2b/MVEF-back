<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipements extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'hebergement_id'
    ];

    public function destination()
    {
        return $this->belongsToMany(Hebergement::class);
    }
}
