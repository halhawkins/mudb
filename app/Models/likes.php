<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class likes extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'type',
        'itemID',
        'like',
        'item_name',
        'artist'
    ];
}
