<?php
require_once("../vendor/autoload.php"); 

 use Illuminate\Support\Facades\Route;
 use Aerni\Spotify\Facades\SpotifyFacade as Spotify;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/newreleases',function() {
    $res = Spotify::newReleases()->get();//Spotify::searchArtists('Peter Gabriel')->get();
    return response()->json($res);
});

Route::get('/categories',function() {
    $res = Spotify::categories()->get();
    return response()->json($res);
});

Route::get('/tracks/{query}',function($query) {
    $res = Spotify::searchTracks($query)->get('tracks');
    // $res2 = Spotify::audioAnalysisForTrack($res['items'][0]['id']);
    // return response()->json($res2);
    return response()->json($res);
});

Route::get('/albums/{query}',function($query){
    $res = Spotify::searchAlbums($query)->get();
    return response()->json($res);
});

Route::get('/album/{quert}',function($query){
    $res = Spotify::album($query)->get();
    return response()->json($res);
});

Route::get('/artistalbums/{query}',function($query){
    $res = Spotify::artistAlbums($query)->get();
    return response()->json($res);
});

Route::get('/artists/{query}',function($query){
    $res = Spotify::searchArtists($query)->get();
    return response()->json($res);
});


Route::get('/shows/{query?}',function($query){
    $res = Spotify::searchShows($query)->get();
    return response()->json($res);
});

Route::get('/searchall/{query}',function($query){
    $res = Spotify::searchItems($query,'album, artist, track')->get();
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

