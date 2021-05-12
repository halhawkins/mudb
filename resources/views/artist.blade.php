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
        item_data = "";

        function large_view(){
        $(".artist-cell").addClass("col-lg-4").addClass("col-md-6");
        $(".info-container compact").removeClass("col-9 col-sm-10 col-xl-12").addClass("col-12");
        $(".artist-card,.info-container").removeClass('compact');
        $(".artist-image").removeClass("col-3 col-sm-2 col-lg-2 col-xl-2").addClass("col-12");
        $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $.ajax({
                type: "POST",
                url: "{{url('/')}}/setviewstyle",
                data: {viewstyle:"large"},
                success: function (response) {
                    viewstyle="large";
                }
            });

    }

    function compact_view(){
        $(".artist-cell").removeClass("col-lg-4 col-md-6 col-sm-12").addClass("col-12");
        $(".artist-card,.info-container").addClass('compact');
        $(".info-container").removeClass("col-12").addClass("col-9 col-sm-10 col-xl-10");
        $(".artist-image").removeClass("col-12").addClass("col-3 col-sm-2 col-lg-2 col-xl-2");             
        $.ajaxSetup({
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });
                 $.ajax({
                type: "POST",
                url: "{{url('/')}}/setviewstyle",
                data: {viewstyle:"compact"},
                success: function (response) {
                    viewstyle="compact";
                }
            });
    }

        $(document).ready(function(){
            $(".dripicons-view-thumb").click(function(){
                large_view();
            });
            $(".dripicons-view-list-large").click(function(){
                compact_view();
            });
            artistID = '{{$artistid}}';
            albs = $("#releases");
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/artist/" + artistID,
                success: function (response) {
                    item_data = response;


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
                            app.like({
                                url:"{{url('/')}}",
                                itemType:'artist',
                                itemName: null,
                                itemArtist: response.name,
                                rating: rating,
                                itemId: artistID,
                                itemData: JSON.stringify(item_data),
                            });
                    });

                    $("#artist-image").attr("src",response.images[0].url);
                    backgroundimage = "linear-gradient(to bottom, rgba(245, 246, 252, 0.22), rgba(255, 255, 255, 1)), url(" + response.images[0].url + ")";
                    artistName = response.name;
                    $(".artist-jumbo").css("background-image",backgroundimage);
                    $(".artist-jumbo").css("background-size","cover");
                    $("#artist-name").html(artistName);
                    bioURL = "https://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" + encodeURIComponent(artistName) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                    $.ajax({
                        type: "GET",
                        url: bioURL,
                            success: function (res2) {
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
                                        spotifyUrl = album.uri;
                                        albumArtists = "";
                                        $.each(album.artists, function(i,artguy) {
                                            albumArtists += `<a title="Artist name" href="{{url('/')}}/artist/` + artguy.id + `">` + artguy.name + `</a>`;
                                            if(album.artists.length > (i+1))
                                            albumArtists += ", ";
                                        })
                                        content = 
                                        `
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 artist-cell"> 

                                        <div class="col-12 artist-card compact">
                                            <div class="row">
                                                <div class="col-3 col-sm-2 col-xl-2 artist-image">
                                                    <a href="{{url('/')}}/album/` + album.id + `">
                                                    <img src="` + image + `" alt="album cover"></a>
                                                </div>
                                                <div class="col-9 col-sm-10 col-xl-10 info-container compact">
                                                    <a href="{{url('/')}}/album/` + album.id + `">
                                                    <h5 class="track-name">` + album.name + `</h5></a>
                                                            <em>`+ albumArtists +`</em><br>
                                                    <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`




                                        albs.append(content);                        
                                        });
                                        url = "{{url('/')}}/artist/" + artistID + "/";
                                        if(viewstyle === "compact")
                                            compact_view();       
                                        else   
                                            large_view();         
                                        $("#artist").append(paginate(url,totalAlbums,page,perPage,8)); 

                                        $.ajax({
                                            type: "GET",
                                            url: "{{url('/')}}/rating/{{$artistid}}/artist",
                                            success: function (likes) {
                                                like = parseInt(likes.like)
                                                if(like === 1){
                                                    $(".dripicons-thumbs-up").css("color","#00FF00");
                                                    $(".dripicons-thumbs-down").css("color","#808080");
                                                }
                                                else if(like === -1){
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
        <div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="artist-name" class="panel-heading"></h3></div><div class="col-6"><em class="btn float-right icon dripicons-view-thumb" title="Full Size Panel View"></em><em class="btn float-right icon dripicons-view-list-large"  title="Compact View"></em></div></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
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
