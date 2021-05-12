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
    </style>
<script src="{{url('/')}}/js/app.js"></script>
    <script>
    function playOnSpotify(id){
        window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
        alert(id);
    }
    itemData = "";

        $(document).ready(function(){
            $("img.play-on-spotify").click(function(){
            });
            trackId = '{{$trackid}}';
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/track/" + trackId,
                success: function (response) {
                    itemData = response;
                    isrc = response.external_ids.isrc;
                    $("#artist-image").attr("src",response.album.images[0].url);
                    albumName = response.album.name;
                    albumType = response.album.album_type;
                    trackName = response.name;
                    artistArray = response.artists;
                    releaseYear = new Date(response.album.release_date).getFullYear();
                    artists = "";
                    $("#track-info").append(`<a href="` + response.uri + `"><img src="{{url("/")}}/images/Spotify_play.png" style="width:32px;height:auto;"><span style="font-size:1.2em;"> Play on Spotify</span></a>`);
                    textArtists = "";
                    $.each(artistArray,function(i,artist){
                        textArtists += artist.name;
                        artists += `<a href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                        if(artistArray.length > (i+1)){
                            artists += ", ";
                            textArtists += ", ";
                        }
                    });


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
                                    itemType:'track',
                                    itemName: trackName,
                                    itemArtist: textArtists,
                                    rating: rating,
                                    itemId: '{{$trackid}}',
                                    itemData: JSON.stringify(itemData),
                                }
                                    );
                            // app.like("{{url('/')}}","{{$trackid}}","track",rating);
                    });


                    $.ajax({
                        type: "GET",
                        url: "{{url('/')}}/rating/" + trackId + "/track",
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
                    $.ajax({
                        type: "GET",
                        url: "{{url('/')}}/api/trackvideo/" + isrc,
                        success: function (res) {
                            $.each(res,function(i,val){
                                $("#releases").html(`
                                    <div class="videoWrapper">
                                    <iframe
                                        src="https://www.youtube.com/embed/` + val.id.videoId + `">
                                    </iframe>
                                    </div>
                                <div class="col-12" id="track-info">
                                    <h4>` + trackName + `</h4><br/>
                                </div>
                                `);
                            });
                        }
                    });
                    $("#artist-image").parent().append(`<h4><a href="{{url("/")}}/album/` + response.album.id + `">` + albumName + "</a></h4><em>" + albumType + "</em><br/>("+releaseYear+")<br/><h5>" + artists + "</h5>");
                    additionalInfo = $("#releases").parent().append(`<div class="row"></div>`);
                    // additionalInfo.append("<div class='col-12'><pre>"+JSON.stringify(response,null,"\t")+"</pre></div>");
                }
            })
        });
        
    </script>
@endsection

@section('mainbody')
        <div class="col-md-12 artist-info"><h3 id="artist-name-heading"></h3>
                <div class="row" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3 primary-bg" style="padding-bottom:0px;">
                        <h3 id="artist-name"></h3>
                        <img class="img-fluid" id="artist-image" src="/assets/images/generic-user-icon-19.jpg">
                        @auth
                        <div class="m-2"><em class="dripicons-thumbs-up" title="I like this"></em>&nbsp;&nbsp;&nbsp;<em class="dripicons-thumbs-down" title="I dislike this"></em></div>
                        @endauth
                        <p id="bio"></p>
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
                    </div>

                    <!-- </div> -->
                </div>
            </div>
@endsection
