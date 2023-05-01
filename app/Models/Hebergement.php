<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hebergement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'description',
        'image',
    ];

    public function type()
    {
        return $this->hasOne(Type::class);
    }
}
