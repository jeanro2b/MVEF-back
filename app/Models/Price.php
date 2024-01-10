<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'hebergement_id',
        'reduction',
        'start',
        'end'
    ];

    public function hebergement()
    {
        return $this->hasOne(Hebergement::class);
    }
}
