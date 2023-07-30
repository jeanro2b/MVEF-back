<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'mail',
        'phone',
        'name',
        'number',
        'planning_id'
    ];

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }
}
