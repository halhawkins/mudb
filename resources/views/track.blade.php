@extends('layout')

@section('script')
<style>
.videoWrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    padding-top: 25px;
    height: 0;
}
.videoWrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
#track-info h4{
    color: black;
}
.dripicons-thumbs-up{
    cursor: pointer;
}
.dripicons-thumbs-down{
    cursor: pointer;
}
.comment-avatar{
    border-radius: 100%;
    display: inline;
    margin-left:.5em;
}
.comment-name{
    /* font-size:.75em; */
    margin-left:.5em;
}
ul{
    /* margin-left: 0 !important; */
    list-style-position: outside;
}
li{
    margin-top: 1rem;
    margin-left: 0 !important;
    /* display:inline; */
    list-style: none;
    font-size: .85rem;
}
.comment-icons{
    margin-left: .5rem;
    margin-right: .5rem;
    cursor: pointer;
    padding: 2px;
}
.comment-icons:hover{
    border: 1px solid grey;
    border-radius: 4px;
}
.btn-comment{
    font-size: .8rem;
    margin-left: .25rem;

}
.content-wrapper{
    margin-left:1rem;
    position:relative;
}
.context-menu-item{
    margin-top:.25rem;
    margin-bottom:.25rem;
    margin-left:0;
}
.menuclass{
    position: absolute;
    padding-left:12px;
    padding-right:12px;
    background-color: white;
    -webkit-box-shadow: 8px 8px 5px -3px rgba(0,0,0,0.59); 
    box-shadow: 8px 8px 5px -3px rgba(0,0,0,0.59);
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    font-weight: 400;
}
.menuclass li:hover{
    background: #333;
    color:white;
}

.activated{
    color: red;
}
</style>

<script src="{{url('/')}}/js/app.js"></script>
<script src="{{url('/')}}/js/track.js"></script>

<script>

function playOnSpotify(id){
    window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
}

itemData = "";

const itemId = "{{$trackid}}";

$(document).ready(function(){
    @guest
        userid = 0;
        $.ajax({
            type: "get",
            url: "{{url('/')}}/comments/" + itemId,
            success: function (response) {
                showComments(response, "commentdiv",userid,"{{url('/')}}",null);
            }
        });
    @endguest 
    @auth 
        userid = {{Auth::user()->id}};
    $.ajax({
        type: "get",
        url: "{{url('/')}}/comments/" + itemId,
        success: function (response) {
            showComments(response, "commentdiv",userid,"{{url('/')}}","{{Auth::user()->avatar}}");
        }
    });
    @endauth 
    getTrackData('{{$trackid}}',"{{url('/')}}");
    $("img.play-on-spotify").click(function(){
    });
});
        
</script>
@endsection

@section('mainbody')
<div class="col-md-12 artist-info"><h3 id="artist-name-heading"></h3>
    <div class="row" id="artist" style="padding-bottom:0px;">
        <div class="col-md-3 col-lg-3 col-xl-3 primary-bg" style="padding-bottom:0px;">
            <div class="row">
                <div class="col-12">
                    <h3 id="artist-name"></h3>
                    <img class="img-fluid" id="artist-image" src="/assets/images/generic-user-icon-19.jpg">
                </div>
            </div>
            @auth
            <div class="m-2"><em class="dripicons-thumbs-up" title="I like this"></em>&nbsp;&nbsp;&nbsp;<em class="dripicons-thumbs-down" title="I dislike this"></em></div>
            @endauth
            <div id="bio" class="w-100">
            </div>
        </div>

        <div class="col-md-9 col-lg-9 col-xl-9 aux-bg1">
                    <div class="row">
                        <div class="col-12" id="releases">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="track-info">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-dark">
                            <h5>Comments</h5>
                            @auth
                            <p>Leave new comment. <i onclick="leaveComment(null,'{{url('/')}}','{{Auth::user()->avatar}}')" class="comment-icons fas fa-comment-dots"></i></p>
                            @endauth
                            <ul id="commentdiv">
                            </ul>
                        </div>
                    </div>
        </div>

        <!-- </div> -->
    </div>
</div>
@endsection
@section("modals")
<div id="award-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Login</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div id="report-comment-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Report Comment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- <form id="report-comment-form"> -->
                    <textarea id="reason-for-report-input" class="form-control w-100"></textarea>
                    <input id="comment-id-input" type="hidden"><br>
                    <button id="submit-report-comment" class="btn btn-sm btn-primary">Submit</button>
                    <button id="cancel-report-comment" class="btn btn-sm btn-default">Cancel</button>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
@endsection
