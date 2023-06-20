<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'languages',
        'mail',
        'reception',
        'arrival',
        'departure',
        'carte',
        'pImage',
        'sImage',
        'tImage1',
        'tImage2',
        'vehicule',
        'parking',
        'favorite'
    ];

    public function service()
    {
        return $this->belongsToMany(Service::class);
    }

    public function retours()
    {
        return $this->hasMany(Retours::class);
    }

    public function hebergement()
    {
        return $this->hasMany(Hebergement::class);
    }

    
}
