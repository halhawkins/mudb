<?php

 use Illuminate\Support\Facades\Route;
 use Illuminate\Http\Request;
 use App\Http\Controllers\UserController;
 use App\Http\Controllers\LoginController;
 use App\Http\Controllers\FileUploadController;
 use Laravel\Socialite\Facades\Socialite;
 use App\Http\Controllers\LikeController;
 use Aerni\Spotify\Facades\SpotifyFacade as Spotify;
 use Aerni\Spotify\Facades\SpotifySeedFacade as SpotifySeed;
 use App\Mail\VerifyEmail;
 use Illuminate\Support\Facades\Mail;
 use App\Http\Controllers\VerifyEmailController;
 use App\Http\Controllers\PasswordChangeEmailController;
 /**
  * Display the default page
  */
Route::get('/', function () {
    if(Auth::check()){
        $l = new LikeController();
        if($l->likesCount() > 0)
            return view('/recommendations')->with("perpage",20);
        else
            return view('/top200')->with('page',1)->with('perpage',20);
    }
    else
        return view('/top200')->with('page',1)->with('perpage',20);
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
Route::get('/artist/{artistid}/{page?}/{perpage?}', function($artistid,$page=1,$perpage=20){
    return view('artist')->with('artistid',$artistid)->with('page',$page)->with('perpage',$perpage);
});

/**
 * Return an album page
 */
Route::get('/album/{albumid}/{page?}/{perpage?}', function($albumid,$page=1,$perpage=20){
    return view('album')->with('albumid',$albumid)->with('page',$page)->with('perpage',$perpage);
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

Route::get('/newreleases/{page?}/{perpage?}',function($page=1,$perpage=20){
    return view('newreleases')->with('page',$page)->with('perpage',$perpage);
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
    // $items = [];
    $limit = 1000;
    $page=0;
    $jsonarray = json_decode(file_get_contents(str_replace("\\","/",\storage_path("app/categories/"))."categories.json"),true);
    $items = $jsonarray['categories']['items'];
    return response()->json($items);
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

Route::post('/rateitem', [LikeController::class,'RateItem']);

Route::get('/rating/{itemid}/{type}', function($itemid,$type){
    $i = new LikeController;
    $t = $i->getRating($itemid,$type);
    return response()->json($t);
}); 

Route::get('/liked',function(){
    return view('liked');
});

Route::get('/likedtracks/{page?}/{perPage?}', function($page=1,$perPage=20){
    return view('likedtracks')->with("page",$page)->with("perPage",$perPage);
});

Route::get('/likedalbums/{page?}/{perPage?}', function($page=1,$perPage=20){
    return view('likedalbums')->with("page",$page)->with("perPage",$perPage);
});

Route::get('/likedartists/{page?}/{perPage?}', function($page=1,$perPage=20){
    return view('likedartists')->with("page",$page)->with("perPage",$perPage);
});

Route::get('/userlikes/{type}/{page?}/{perpage?}',function($type,$page=1,$perPage=20){
    $i = new LikeController;
    $res = $i->userLikes($type,$page,$perPage);
    return response()->json($res);
});

Route::post('/deltags',[LikeController::class,'delTags']);
Route::get('/getusertags',function(){
    $l = new LikeController;
    $res = $l->getUserTags();
    return response()->json($res);
});

Route::get('/likesinfo', function(){
    $likes = $_REQUEST['likes'];
    $l = new LikeController;
    $res = $l->likesInfo($likes);
    return response()->json($res);
});

Route::post('/deltag', function(){
    $t = $_REQUEST['tagid'];
    $l = new LikeController;
    $res = $l->delTag($t);
});

Route::get('/recommendations',function(){
    return view('recommendations')->with('page',1)->with('perpage',20);
});

Route::post('/setviewstyle', function(){
    $mode = $_REQUEST['viewstyle'];
    session(['viewstyle'=>$mode]);
});

Route::get("/personal",function(){
    $l = new LikeController;
    $tags = implode(', ',$l->getUserTags());
    $artists = $l->getUserArtists();
    shuffle($artists);
    $tracks = $l->getUserTracks();
    shuffle($tracks);
    $pos = rand(0,5);
    $trackseeds = array_slice($tracks,0,$pos);
    $artistseeds = array_slice($artists,0,5-$pos);
    // $seedo = new SpotifySeed;
    $seed = SpotifySeed::
        // setGenres($tags)
        // ->
        setArtists($artistseeds)
        ->
        setTracks($trackseeds)
        ;
    $res = Spotify::recommendations($seed)->get();
    return response()->json($res);
});

Route::get('/tos',function(){
    return view('tos');
});

Route::get('/privacy',function(){
    return view('privacy');
});

Route::get('/register',function(){
    return view('register');
});

Route::get('/verify/{id}/{hash}',function($id,$hash){
    $r = new VerifyEmailController();
    $res = $r->verifyAccount($id,$hash);
    if($res['code'] == 200)
        return view('verified');
    else
        return view('resend');
});

Route::get('/passwordreq/{email}', function($email){
    $r = new PasswordChangeEmailController($email);
    $r->SendEmail($email);
    return response()->json(array("result"=>"success"));
});

Route::get('/passwordchange/{token}', function($token){
    $r = new PasswordChangeEmailController();
    $res = $r->CheckPasswordChangeToken($token);
    if($res['status'] == "success")
        return view('passwordchange')->with("token",$token);
    else
        return view('error')->with("message",$res['message']);
});

Route::post('/updatepassword',function(){
    $r = new PasswordChangeEmailController();
    $newpassword = $_REQUEST['newPassword'];
    $confirm = $_REQUEST['confirm'];
    $token = $_REQUEST['token'];
    if($newpassword !== $confirm)
        return response()->json(array("status" =>"error", "message" => "New password and confirm password do not match."),400);
    $res = $r->UpdatePassword($token,$newpassword);
    if($res["status"] == "success")
        return response()->json($res,200);
    else
        return response()->json($res,400);
});

Route::get('/resetpassword', function(){
    return view('resetpassword');
});

Route::get('/testemail',function(){
    $r = new VerifyEmailController();
    $r->SendEmail("1","hal@localhost.com");
    return view('email');
    // Mail::to('hal@localhost.com')->send(new VerifyEmail());
});
