<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hebergement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'long_title',
        'city',
        'description',
        'pImage',
        'sImage',
        'tImage',
        'code',
        'destination_id',
        'type_id',
        'price',
        'couchage'
    ];

    public function type()
    {
        return $this->hasOne(Type::class);
    }

    public function destination()
    {
        return $this->hasOne(Destination::class);
    }

    public function planning()
    {
        return $this->hasMany(Planning::class);
    }

    public function reservation()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
