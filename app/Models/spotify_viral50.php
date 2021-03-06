<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spotify_viral50 extends Model
{
    use HasFactory;


    function __construct(){
        $this->setTable('spotify_viral50');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'track_name',
        'artist',
        'streams',
        'spotify_id',
        'spotify_data',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    /**
     * getSpotifyDataAttribute
     * 
     * accessor to transform JSON data from database back
     * to an array
     *
     * @param [string] $value json field data
     * @return array
     */
    public function getSpotifyDataAttribute($value){
        return json_decode($value,true);
    }
}
