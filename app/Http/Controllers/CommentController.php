<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Commentlikes;
use Illuminate\Http\Request;
use Elegant\Sanitizer\Sanitizer;

/**
 * using this sanitizer:
 *  https://github.com/elegantweb/sanitizer
 */


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $filters = [
        'comment_body' => 'strip_tags'
    ];
    public function index()
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
        $cleaned = new Sanitizer(array('comment_body'=>$_REQUEST['comment_body']),$this->filters);
        //
        $comment = new Comment();
        $comment->topic_item_id = $_REQUEST['item_id']; // what are we commenting on?
        $comment->user_id = Auth::user()->id;
        $comment->parent_comment = isset($_REQUEST['parent_comment']) ? $_REQUEST['parent_comment'] : null;
        if(empty($_REQUEST['parent_comment']))
            $comment->parent_comment = null;
        $comment->comment_body = $cleaned->sanitize()['comment_body'];
        $links = $this->makeLinksClickable($comment->comment_body);
        $comment->comment_body = $links['link'];
        $comment->save();
        $res = array("comment"=>$this->getComment($comment->id),$links['meta_data']);
        // $res['user'];
        return \response()->json($res);
    }

    protected function getHttpResponseCode_using_curl($url, $followredirects = true){
        if(! $url || ! is_string($url)){
            return false;
        }
        $ch = @curl_init($url);
        if($ch === false){
            return false;
        }
        @curl_setopt($ch, CURLOPT_HEADER         ,true);    // we want headers
        @curl_setopt($ch, CURLOPT_NOBODY         ,true);    // dont need body
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);    // catch output (do NOT print!)
        if($followredirects){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);
            @curl_setopt($ch, CURLOPT_MAXREDIRS      ,10);  // fairly random number, but could prevent unwanted endless redirects with followlocation=true
        }else{
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,false);
        }
        @curl_exec($ch);
        if(@curl_errno($ch)){   // should be 0
            @curl_close($ch);
            return false;
        }
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); // note: php.net documentation shows this returns a string, but really it returns an int
        @curl_close($ch);
        return $code;
    }
    
    protected function makeLinksClickable($str){
        $rex = '@(http(s)?://)?(([a-zA-Z0-9])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        if(preg_match($rex,$str,$matches)){
            // $head = @get_headers($matches[0]);
            if($this->getHttpResponseCode_using_curl($matches[0]) != 200){
                $meta = null;
            }
            else{
                $meta = \get_meta_tags($matches[0]);//"https://" . 
            }
        }
        else{
            $meta = null;
        }
        $out = \preg_replace($rex, '<a href="http$2://$3" target="_blank">$0</a>',$str);
        $res = array("link"=>$out,"meta_data"=>$meta);
        
        return $res;
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getComment($id){
        $comment = Comment::where('id','=',$id)->first();
        $user = User::where('id','=',$comment->user_id)->first();
        $liked = Commentlikes::where("item_id","=",$id)->where("user_id","=",Auth::user()->id)->where("award_type","=","like")->count();
        if($liked > 0)
            $iliked = true;
        else
            $iliked = false;
        $likes = Commentlikes::where("item_id","=",$id)->where("award_type","=","like")->count();
        $comment['likes'] = $likes;
        $comment['liked'] = $iliked;
        return array("user"=>$user,"comment"=>$comment);
    }

    /**
     * Find comments associated with an item
     *
     * @param String $item_id
     * @param int $depth
     * @return array $results
     */
    public function getItemComments($item_id){
        // $item_id = $request->item_id;
        $list = array();
        $list = $this->getReplies($list,0,$maxdepth=3,$item_id,null);
        return response()->json($list);
    }

    public function getReplies($array,$depth,$maxdepth=3,$item_id,$parent_id){

        if($depth <= $maxdepth){
            $response = array();
            $replies = Comment::where("topic_item_id","=",$item_id)->where("parent_comment","=",$parent_id)->get()->toArray();

            
            foreach ($replies as $reply) {
                $reply['user'] = User::where("id","=",$reply['user_id'])->first();
                if(Auth::check())
                    $liked = Commentlikes::where("item_id","=",$reply['id'])->where("user_id","=",Auth::user()->id)->where("award_type","=","like")->count();
                else
                    $liked = 0;
                if($liked > 0)
                    $iliked = true;
                else
                    $iliked = false;
                $likes = Commentlikes::where("item_id","=",$reply['id'])->where("award_type","=","like")->count();
                $reply['likes'] = $likes;
                $reply['liked'] = $iliked;
                $response[] =  $reply;
                $res = $this->getReplies($replies,$depth+1,$maxdepth,$item_id,$reply['id']);
                if(!empty($res))  
                    $response[] = $res;
            }
            return $response;
        }
        return null;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $user = Auth::user();
        Comment::where("id","=",$_REQUEST['id'])->where("user_id","=",$user->id)->update(["comment_body"=>$_REQUEST['comment_body']]);
        $comment = $this->getComment($_REQUEST['id']);
        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        $user = Auth::user();
        $comment = Comment::where("id","=",$request->id)->where("user_id","=",$user->id)->getRepliesdelete();
        return response()->json($comment);
    }
}
