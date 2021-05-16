<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyEmails extends Model
{
    use HasFactory;

    function __construct(){
        $this->setTable('verification');
    }
    protected $fillable = [
        'userID',
        'email',
        'expires_at',
        'verification_token',
    ];



}
