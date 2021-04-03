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

Route::get('/searchall/{query}',function($query){
    $res = Spotify::searchItems($query,['album', 'artist', 'track'])->get();
    return response()->json($res);
});

Route::get('/album/{query}',function($query){
    $res = Spotify::album($query)->get();
    return response()->json($res);
});

Route::get('/artist/{query}',function($query){
    // Application name	Muzicor
    // API key	40e7023497e3403fc3d672679eba6f03
    // Shared secret	eb4c7ec7c11013b9322f894900edd3dc
    // Registered to	halberthawkins
    // https://www.last.fm/api/webauth

    // Application name	Muzicor
    // API key	a480efb1010bffbf654c1791ac72543d
    // Shared secret	6efc7393dabc439b46e84c80e2cabc1b
    // Registered to	halberthawkins
    $res = Spotify::artist($query)->get();
    return response()->json($res);
});
