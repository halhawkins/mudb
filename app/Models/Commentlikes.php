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
        'award_type' // like β€οΈ, trophy π, primrose π΅οΈ, medal ποΈ, ribbon ποΈ, crown π, starπ
    ];
}
