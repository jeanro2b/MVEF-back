<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retours extends Model
{
    use HasFactory;

    protected $fillable = [
        'text'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
