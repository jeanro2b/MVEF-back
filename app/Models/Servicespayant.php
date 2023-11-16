<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicespayant extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'text',
        'destination_id'
    ];

    public function destination()
    {
        return $this->belongsToMany(Destination::class);
    }
}
