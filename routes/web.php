<?php

 use Illuminate\Support\Facades\Route;
 use Illuminate\Http\Request;
 use App\Http\Controllers\UserController;
 use App\Http\Controllers\LoginController;
 use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('newreleases');
});

Route::get('/hal', [UserController::class,'index']);

Route::post('/register',[UserController::class,'register']);

Route::get('/artist/{artistid}', function($artistid){
    return view('artist')->with('artistid',$artistid);
});

Route::get('/album/{albumid}', function($albumid){
    return view('album')->with('albumid',$albumid);
});

Route::get('/search',function(Request $req) {
    $query = $req->query('query');
    return view('search')->with("query",$query);
});

Route::get('/track/{trackid}',function($trackid){
    return view('track')->with('trackid',$trackid);
});

Route::get('/categories',function() {
    $res = Spotify::categories()->get();
    return response()->json($res);
});

Route::get('/auth/redirect', function () {
    // dd($_REQUEST)
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function (Request $request) {
    $user = Socialite::driver('google')->user();
    $request->session()->put('username',$user['name']);
    $request->session()->put('avatar',url('/')."/images/noartistimage.png");
    return view("newreleases");
    // $user->token
});

Route::get('/auth/user',function(){
    $user = Socialite::driver('google')->user();
    return response()->json($user);
});

Route::get('/register',function(){
    return view('register');
});
