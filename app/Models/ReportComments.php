<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_making_report',
        'comment_id',
        'reason_for_report',
        'review',
    ]; 
}
