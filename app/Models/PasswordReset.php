<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['email', 'token'];

    public static function createToken($email)
    {
        return static::updateOrInsert(
            ['email' => $email],
            ['token' => Str::random(60)]
        );
    }
}
