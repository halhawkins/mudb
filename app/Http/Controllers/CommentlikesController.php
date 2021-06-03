<?php

namespace App\Http\Controllers;

use App\Models\Commentlikes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentlikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $cnt = Commentlikes::where("user_id","=",Auth::user()->id)->where("item_id","=",$_REQUEST['item_id'])->where("award_type","=",$_REQUEST['award_type'])->count();
        if($cnt == 0){
            $cl = new Commentlikes;
            $cl->user_id=Auth::user()->id;
            $cl->item_id=$request->item_id;
            $cl->award_type=$request->award_type;
            $cl->save();
        }
        else
            Commentlikes::where("user_id","=",Auth::user()->id)->where("item_id","=",$request->item_id)->where("award_type","=",$request->award_type)->delete();
        $res = $this->getCommentlikes($request->item_id);
        return $res;
    }

    protected function getCommentlikes($item_id){
        // like ❤️, trophy 🏆, primrose 🏵️, medal 🎖️, ribbon 🎗️, crown 👑, star🌟
        $likes = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","like")->count();
        $trophies = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","trophy")->count();
        $primroses = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","primrose")->count();
        $medals = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","medal")->count();
        $ribbons = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","ribbon")->count();
        $crowns = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","crown")->count();
        $stars = Commentlikes::where("item_id","=",$item_id)->where("award_type","=","star")->count();
        $userlikes = Commentlikes::where("user_id","=",Auth::user()->id)->where("item_id","=",$item_id)->where("award_type","=","like")->count(); 
        return array(
            "userlikes"=>$userlikes,
            "likes"=>$likes,
            "trophies"=>$trophies,
            "primroses"=>$primroses,
            "medals"=>$medals,
            "ribbons"=>$ribbons,
            "crowns"=>$crowns,
            "stars"=>$stars
        );
    }

    public function getHTML($item_id){
        $r = $this->getCommentlikes($item_id);
        $res = "";
        if($r->likes > 0)
            $res =+ "❤️<span class='likecount'>" . $r->likes . "</span>";
        if($r->trophy > 0)
            $res =+ "🏆<span class='likecount'>" . $r->trophy . "</span>";
        if($r->primrose > 0)
            $res =+ "🏵️<span class='likecount'>" . $r->primrose . "</span>";
        if($r->medal > 0)
            $res =+ "🎖️<span class='likecount'>" . $r->medal . "</span>";
        if($r->ribbon > 0)
            $res =+ "🎗️<span class='likecount'>" . $r->ribbon . "</span>";
        if($r->crown > 0)
            $res =+ "👑<span class='likecount'>" . $r->crown . "</span>";
        if($r->star > 0)
            $res =+ "🌟<span class='likecount'>" . $r->star . "</span>";
        return $res;
    }
}
