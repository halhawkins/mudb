<?php

namespace App\Http\Controllers;
use illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\spotify_top200;
use App\Models\spotify_viral50;
use Illuminate\Support\Carbon;

class SpotifyController extends Controller
{
    public $datadate;
    function __construct(){
        // datadate is the date of the most recent successful import
        $this->datadate = \file_get_contents(\base_path()."/importdate.dat") . " 00:00:00";
    }
    //
    public function viral($page=1,$perpage=20){
        // $spotify_viral50 = spotify_viral50::select('position','spotify_data')->whereDate('created_at', Carbon::today())->orderBy('position')->get();
        $spotify_viral50 = spotify_viral50::select('position','spotify_data')->whereDate('created_at', '=', $this->datadate)->orderBy('position','asc')->get();
        $count = count($spotify_viral50);
        $chunks = array_chunk(json_decode(json_encode($spotify_viral50),true),$perpage);
        $res = $chunks[$page-1];
        $total = array("viral50_for_date"=> $this->datadate,"total_count"=>$count,"tracks"=>$res);
        return response()->json($total);
    }
    //
    public function top200($page=1,$perpage=20){
        // getting the results for the most recent successful import
        $top200 = spotify_top200::select('position','streams','spotify_data')->where('created_at','>=', $this->datadate)->orderBy('position','asc')->get();
        // $top200 = spotify_top200::select('position','streams','spotify_data')->whereDate('created_at', Carbon::today())->orderBy('position','asc')->get();
        $count = count($top200);
        $chunks = array_chunk(json_decode(json_encode($top200),true),$perpage);
        $res = $chunks[$page-1];
        $total = array("top200_for_date"=> $this->datadate,"total_count"=>$count,"tracks"=>$res);
        return response()->json($total);
    }
}
