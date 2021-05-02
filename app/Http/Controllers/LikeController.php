<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //
    public function RateItem(){ //$itemid,$type,$rating
        if(Auth::check()){
            $userid = Auth::user()->id;
            $itemid = $_REQUEST['itemid'];
            $type = $_REQUEST['type'];
            $rating = $_REQUEST['rating'];
            $itemName = $_REQUEST['itemName'];
            $itemArtist = $_REQUEST['itemArtist'];
            if(empty($itemName))
                $itemName = null;
            if(empty($itemArtist))
                $itemArtist = null;
            if($rating == 0){
                likes::where('userId','=',$userid)->where('itemID','=',$itemid)->where('type','=',$type)->delete();
            }
            else{
                likes::updateOrCreate(
                    ['userID'=>$userid,'itemID'=>$itemid,'type'=>$type],
                    ['like'=>$rating,'item_name'=>$itemName,'artist'=>$itemArtist]
                );
            }
            // likes::where('userID',$userid)
            //     ->where('itemID',$itemid)
            //     ->where('type',$type)
            //     ->update('rating',$rating);
            // $like = new likes;
            // $like->itemID = $itemid;
            // $like->type = $type;
            // $like->userID = $userid;
            // $like->save();

            return response()->json(array("part1"=>$itemid,"part2"=>$type,"part3"=>$rating,"user"=>Auth::user()));
        }
        else
            return response(400)->json(array("error"=>"No authenticated user."));
    }

    public function likesInfo($itemsArray){
        foreach ($itemsArray as $value) {
            $res[] = likes::where("type","=",$value['type'])->where("itemID","=",$value['id'])->get();
        }
        return $res;
    }

    public function delTags(){
        $userid = Auth::user()->id;
        likes::where("userID","=",$userid)->where("type","=","tag")->delete();
    }

    public function delTag($tagid){
        $userid = Auth::user()->id;

        likes::where("userID","=",$userid)->where("type","=","tag")->where("itemID","=",$tagid)->delete();
    }

    public function getUserTags(){
        $tags = DB::table('likes')->where("userID","=",Auth::user()->id)->where("type","=","tag")->pluck('itemID');
        return json_decode(json_encode($tags,true));
    }

    public function getUserArtists(){
        $artists = DB::table('likes')->where("userID","=",Auth::user()->id)->where("type","=","artist")->pluck('itemID');
        return json_decode(json_encode($artists,true));
    }

    public function getUserTracks(){
        $tracks = DB::table('likes')->where("userID","=",Auth::user()->id)->where("type","=","track")->pluck('itemID');
        return json_decode(json_encode($tracks,true));
    }

    public function getRating($itemid,$type){
        $stats = likes::select(DB::raw("count(*) as `total`, count(IF(`like` > 0, 1, null)) as `likes`, count(IF(`like` < 0, 1, null)) as `hates`"))
            ->where("type","=",$type)
            ->where("itemID","=",$itemid)->get();
        if(Auth::check()){
            $userid = Auth::user()->id;
            $like = DB::table('likes')->where('userID',"=",$userid)
                ->where('itemID',"=",$itemid)
                ->where('type',"=",$type)->value('like');
            
            return array("stats"=>$stats[0],"like" => $like);

        }
        else{
            return array("stats"=>$stats[0],"like"=>0);
        }
    }

    public function testRate(){
        $user = Auth::user();
        return response()->json($user);

    }
}
