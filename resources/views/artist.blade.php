@extends('layout')

@section('script')
<style>
.dripicons-thumbs-up{
    color:#808080;
    cursor: pointer;
}
.dripicons-thumbs-down{
    color: #808080;
    cursor: pointer;
}
</style>
<script src="{{url('/')}}/js/app.js"></script>
    <script>
        @if(Session::has('viewstyle'))
        viewstyle = "{{session('viewstyle')}}";
        @else
        viewstyle = "fat";
        @endif
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".dripicons-thumbs-up, .dripicons-thumbs-down").click(function(){
                    if($(this).attr("class") === 'dripicons-thumbs-up'){
                        if($(this).css("color")==="rgb(128, 128, 128)"){
                            rating = 1;
                        }
                        else{
                            rating = 0;
                        }
                    }
                    else{
                        if($(this).css("color")==="rgb(128, 128, 128)"){
                            rating = -1;
                        }
                        else{
                            rating = 0;
                        }
                    }
                    app.like("{{url('/')}}","{{$artistid}}","artist",rating);
            });
            artistID = '{{$artistid}}';
            albs = $("#releases");
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/artist/" + artistID,
                success: function (response) {
                    $("#artist-image").attr("src",response.images[0].url);
                    backgroundimage = "linear-gradient(to bottom, rgba(245, 246, 252, 0.22), rgba(255, 255, 255, 1)), url(" + response.images[0].url + ")";
                    artistName = response.name;
                    $(".artist-jumbo").css("background-image",backgroundimage);
                    $(".artist-jumbo").css("background-size","cover");
                    $("#artist-name-heading").html(artistName);
                    bioURL = "https://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" + encodeURIComponent(artistName) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                    $.ajax({
                        type: "GET",
                        url: bioURL,
                            success: function (res2) {
                             console.log(res2.artist.bio.summary);
                            page = {{$page}};
                            perPage = {{$perpage}};
                            $("#bio").html(res2.artist.bio.summary);
                            $.ajax({
                                type: "GET",
                                url: "{{url('/')}}/api/artistalbums/" + artistID + "/" + page + "/" + perPage,
                                success: function (res3) {
                                    totalAlbums = res3.total;

                                    content = `<div class="col-md-12"><div class="row">`
                                    $.each(res3.items,function(i,album){
                                        image = album.images[1].url;
                                        releaseDate = new Date(album.release_date);
                                        releaseYear = releaseDate.getFullYear();
                                        content = `<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <div class="col-12 artist-card">
                                                        <img src="` + image + `" style="width:100%;height:auto;">
                                                        <h5><a title="Album name" href="{{url('/')}}/album/` + album.id + `">` + album.name + `</a></h5>
                                                        `
                                        + "(" + releaseYear + `)<br/>
                                        `;
                                        $.each(album.artists, function(i,artguy) {
                                            content = content + `<a title="Artist name" href="{{url('/')}}/artist/` + artguy.id + `">` + artguy.name + `</a>`;
                                            if(album.artists.length > (i+1))
                                                content = content + ", ";
                                        })
                                        content = content + `<br/><a href="` + album.uri + `" target="_blank"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a>`;
                                        content = content + `</div>
                                                            </div>
                                                `;
                                        albs.append(content);                        
                                        });
                                        url = "{{url('/')}}/artist/" + artistID + "/";
                                        $("#artist").append(paginate(url,totalAlbums,page,perPage,8)); 

                                        $.ajax({
                                            type: "GET",
                                            url: "http://localhost/mudb/public/rating/{{$artistid}}/artist",
                                            success: function (likes) {
                                                if(likes.like === 1){
                                                    $(".dripicons-thumbs-up").css("color","#00FF00");
                                                    $(".dripicons-thumbs-down").css("color","#808080");
                                                }
                                                else if(likes.like === -1){
                                                    $(".dripicons-thumbs-up").css("color","#808080");
                                                    $(".dripicons-thumbs-down").css("color","#FF0000");
                                                }
                                                else{
                                                    $(".dripicons-thumbs-up").css("color","#808080");
                                                    $(".dripicons-thumbs-down").css("color","#808080");
                                                }
                                            }
                                        });

                                }
                            });
                        },
                        fail:function(){
                            console.log("fail");
                        }
                    });
                    followers = response.followers.total;
                    tags = response.genres;
                    }
                })
            });
        
    </script>
@endsection

@section('mainbody')
        <div class="col-md-12 artist-info primary-bg"><h3 id="artist-name-heading"></h3>
                <div class="row primary-bg" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3 " style="padding-bottom:0px;">
                        <h3 id="artist-name  primary-bg"></h3>
                        <img class="img-fluid" id="artist-image" src="/assets/images/generic-user-icon-19.jpg">
                        @auth
                        <div class="m-2"><em class="dripicons-thumbs-up" title="I like this"></em>&nbsp;&nbsp;&nbsp;<em class="dripicons-thumbs-down" title="I dislike this"></em></div>
                        @endauth
                        <p id="bio"></p>
                    </div>

                    <div class="col-md-9 col-lg-9 col-xl-9 aux-bg1">
                                <div class="row aux-bg1" id="releases">

                               </div>
                    </div>

                    <!-- </div> -->
                </div>
            </div>
@endsection
