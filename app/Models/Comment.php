<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'topic_item_id',
        'parent_comment',
        'user_id',
        'comment_body',
        'award'
    ];
}
