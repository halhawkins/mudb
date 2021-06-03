<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentlikes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'award_type' // like ❤️, trophy 🏆, primrose 🏵️, medal 🎖️, ribbon 🎗️, crown 👑, star🌟
    ];
}
