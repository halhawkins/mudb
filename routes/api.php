<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Aerni\Spotify\Facades\SpotifyFacade as Spotify;
use App\Http\Controllers\SpotifyController;

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

Route::get('/newreleases/{page?}/{perpage?}',function($page=1,$perpage=20) {
    $res = Spotify::newReleases()->limit($perpage)->offset(($page-1)*$perpage)->get();
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

Route::get('/albumtracks/{albumid}/{page?}/{perpage?}',function($albumid,$page=1,$perpage=20){
    $res = Spotify::albumTracks($albumid)->limit($perpage)->offset(($page-1)*$perpage)->get();
    return response()->json($res);
});

Route::get('/artist/{query}',function($query){
    $res = Spotify::artist($query)->get();
    return response()->json($res);
});

Route::get('/albuminfo/{albumartist}/{albumname}',function($albumartist,$albumname){
    $url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&artist=" . urlencode($albumartist) . "&album=" . urlencode($albumname) . "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = json_decode(curl_exec($curl));
    curl_close($curl);
    return response()->json($result);
});

Route::get('/gettracks/{query}/{page}',function($query,$page){
    $res = Spotify::searchTracks($query)->limit(20)->offset(($page-1)*20)->get();

    return response()->json($res);
});

Route::get('/getartists/{query}/{page}',function($query,$page){
    $res = Spotify::searchArtists($query)->limit(20)->offset(($page-1)*20)->get();

    return response()->json($res);
});

Route::get('/getalbums/{query}/{page}',function($query,$page){
    $res = Spotify::searchAlbums($query)->limit(20)->offset(($page-1)*20)->get();

    return response()->json($res);
});

Route::get('/artistalbums/{query}/{page?}/{perpage?}',function($query, $page=1, $perpage=20){
    $res = Spotify::artistAlbums($query)->limit($perpage)->offset(($page-1)*$perpage)->get();
    return response()->json($res);
});

Route::get('/track/{trackid}',function($trackid){
    $res = Spotify::track($trackid)->get();
    return response()->json($res);
});

Route::get('/viral/{page?}/{perpage?}',[SpotifyController::class,'viral']);
Route::get('/top200/{page?}/{perpage?}',[SpotifyController::class,'top200']);

Route::get('/trackvideo/{isrc}',function($isrc){
    $res = Youtube::searchVideos($isrc);
    return response()->json($res);
});

Route::get('/availablegenres',function(){
    $res = Spotify::availableGenreSeeds()->get();
    return response()->json($res);
});

Route::get('/recommendations',function(){
    $seed = SpotifySeed::setGenres(['movies']) //, 'indie-pop''rock', 'pop','alt-rock'
    // ->setArtists(['2qT62DYO8Ajb276vUJmvhz','1YEGETLT2p8k97LIo3deHL','2BGRfQgtzikz1pzAD0kaEn'])
    // ->setTargetValence(1.00)
    // ->setSpeechiness(0.3, 0.9)
    // ->setLiveness(0.3, 1.0)
    ;
    $res= array( 'seed'=>$seed, Spotify::recommendations($seed)->get());
    // $res = 
    return response()->json($res);
});

Route::get('/publicpath', function(){
    return url('/');
});
