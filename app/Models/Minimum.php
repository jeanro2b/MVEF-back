<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minimum extends Model
{
    use HasFactory;

    protected $fillable = [
        'minimum',
        'hebergement_id',
        'month',
        'year'
    ];

    public function hebergement()
    {
        return $this->hasOne(Hebergement::class);
    }
}
