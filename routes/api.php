<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Aerni\Spotify\Facades\SpotifyFacade as Spotify;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/newreleases/',function() {
    $res = Spotify::newReleases()->get();//Spotify::searchArtists('Peter Gabriel')->get();
    return response()->json($res);
});

Route::get('/searchall/{query}',function($query){
    $res = Spotify::searchItems($query,['album', 'artist', 'track'])->get();
    return response()->json($res);
});

Route::get('/album/{query}',function($query){
    $res = Spotify::album($query)->get();
    return response()->json($res);
});

Route::get('/artist/{query}',function($query){
    $res = Spotify::artist($query)->get();
    return response()->json($res);
});

Route::get('/track/{trackid}',function($trackid){
    $res = Spotify::track($trackid)->get();
    return response()->json($res);
});
