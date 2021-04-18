<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spotify_top200 extends Model
{
    use HasFactory;

    function __construct(){
        $this->setTable('spotify_top200');
    }

    // protected $table = 'spotify_top200';
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
        'spotify-data',
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

    public function setspotify_idAttribute($spotify_id){

        $this->attributes['spotify_id'] = \basename($spotify_id);
    }

}
