<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'object',
        'code',
        'status',
        'lit',
        'menage',
        'toilette',
        'hebergement_id',
        'user_id'
    ];

    public function hebergement()
    {
        return $this->hasOne(Hebergement::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function period()
    {
        return $this->hasMany(Period::class);
    }
}
