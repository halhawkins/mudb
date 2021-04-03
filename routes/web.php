<?php
// require_once("../vendor/autoload.php"); 

 use Illuminate\Support\Facades\Route;
 use Illuminate\Http\Request;
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
    return view('newreleases');
});

Route::get('/hal', function () {
    return view('hal');
});

// Route::get('/search', function (Request $request) {
//     // return response()->json($request->input());
//     $query = $request->query('query');
//     return view('search')->with("query",$query);
// });

Route::get('/artist/{artistid}', function($artistid){
    return view('artist')->with('artistid',$artistid);
});

Route::get('/album/{albumid}', function($albumid){
    return view('album')->with('albumid',$albumid);
});

Route::get('/newreleases',function() {
    $res = Spotify::newReleases()->get();//Spotify::searchArtists('Peter Gabriel')->get();
    return response()->json($res);
});

Route::get('/search/{query}',function($query) {
    // $res = Spotify::newReleases()->get();//Spotify::searchArtists('Peter Gabriel')->get();
    // return response()->json($res);
    return view('search')->with("query",$query);
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

// Route::get('api/searchall/{query}',function($query){
//     $res = Spotify::searchItems($query,['album', 'artist', 'track'])->get();
//     return response()->json($res);
// });

