<?php

namespace App\Http\Controllers;
use illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\spotify_top200;
use App\Models\spotify_viral50;
use Illuminate\Support\Carbon;

class SpotifyController extends Controller
{
    //
    public function viral($page=1,$perpage=20){
        $spotify_viral50 = spotify_viral50::select('spotify_data')->whereDate('created_at', Carbon::today())->get();
        $viral50For = date('Y-m-d');
        $count = count($spotify_viral50);
        $chunks = array_chunk(json_decode(json_encode($spotify_viral50),true),$perpage);
        $res = $chunks[$page-1];
        $total = array("viral50_for_date"=>$viral50For,"total_count"=>$count,"tracks"=>$res);
        return response()->json($total);
    }
    //
    public function top200($page=1,$perpage=20){
        $top200 = spotify_top200::select('spotify_data')->whereDate('created_at', Carbon::today())->get();
        $top200For = date('Y-m-d');
        $count = count($top200);
        $chunks = array_chunk(json_decode(json_encode($top200),true),$perpage);
        $res = $chunks[$page-1];
        $total = array("top200_for_date"=>$top200For,"total_count"=>$count,"tracks"=>$res);
        return response()->json($total);
    }
}
