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
            likes::updateOrCreate(
                ['userID'=>$userid,'itemID'=>$itemid,'type'=>$type],
                ['like'=>$rating]
            );
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

    public function delTags(){
        $userid = Auth::user()->id;
        likes::where("userID","=",$userid)->where("type","=","tag")->delete();
    }

    public function delTag($tagid){
        $userid = Auth::user()->id;

        likes::where("userID","=",$userid)->where("type","=","tag")->where("itemID","=",$tagid)->delete();
    }

    public function getUserTags(){
        $tags = likes::select("itemID")->where("userID","=",Auth::user()->id)->where("type","=","tag")->get();
        return $tags;
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
