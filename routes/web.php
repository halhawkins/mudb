<?php

 use Illuminate\Support\Facades\Route;
 use Illuminate\Http\Request;
 use App\Http\Controllers\UserController;
 use App\Http\Controllers\LoginController;
 use App\Http\Controllers\FileUploadController;
 use Laravel\Socialite\Facades\Socialite;

 /**
  * Display the default page
  */
Route::get('/', function () {
    return view('top200');
});

/**
 * display a test page
 */
Route::get('/hal', [UserController::class,'index']);

/**
 * Manually register a new user
 */
Route::post('/register',[UserController::class,'register']);

/**
 * Return an artist page
 */
Route::get('/artist/{artistid}', function($artistid){
    return view('artist')->with('artistid',$artistid);
});

/**
 * Return an album page
 */
Route::get('/album/{albumid}', function($albumid){
    return view('album')->with('albumid',$albumid);
});

/**
 * Return general search results
 */
Route::get('/search',function(Request $req) {
    $query = $req->query('query');
    return view('search')->with("query",$query);
});

/**
 * Return a track page
 */
Route::get('/track/{trackid}',function($trackid){
    return view('track')->with('trackid',$trackid);
});

/**
 * Return a page of track search results
 */
Route::get('/tracks/{query}/{page?}',function($query,$page=1){
    $query = $query;
    $page = $page;
    return view('tracks')->with('query',$query)->with('page',$page);
});

/**
 * Return a page of artist search results
 */
Route::get('/artists/{query}/{page?}',function($query,$page=1){
    $query = $query;
    $page = $page;
    return view('artists')->with('query',$query)->with('page',$page);
});

Route::get('/viral/{page?}/{perpage?}',function($page=1,$perpage=20){
    return view('viral')->with('page',$page)->with('perpage',$perpage);
});

Route::get('/top200/{page?}/{perpage?}',function($page=1,$perpage=20){
    return view('top200')->with('page',$page)->with('perpage',$perpage);
});

/**
 * Return a page of album search results
 */
Route::get('/albums/{query}/{page?}',function($query,$page=1){
    $query = $query;
    $page = $page;
    return view('albums')->with('query',$query)->with('page',$page);
});

/**
 * Return Spotify categories
 */
Route::get('/categories',function() {
    $res = Spotify::categories()->get();
    return response()->json($res);
});

/**
 * End logout and end session
 */
Route::get('/auth/logout',[LoginController::class,'logout']);

/**
 * Redirect to Google for authentication
 */
Route::get('/auth/redirect',[LoginController::class,'redirectToGoogle']);
/**
 * Handle auth results from Google
 */
Route::get('/auth/callback',[LoginController::class,'handleGoogleCallback']);

Route::post('/auth/register',[LoginController::class,'register']);
Route::post('/auth/login',[LoginController::class,'authenticate']);

Route::get('/profile',function(){
    return view('profile');
});

Route::get('file-upload', [FileUploadController::class, 'fileUpload'])->name('file.upload');
Route::post('file-upload', [FileUploadController::class, 'fileUploadPost'])->name('file.upload.post');

Route::get('/register',function(){
    return view('register');
});
