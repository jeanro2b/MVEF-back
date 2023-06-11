<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retours extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'destination_id'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
