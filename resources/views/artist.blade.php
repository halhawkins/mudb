@extends('layout')

@section('script')
    <script>
    function playOnSpotify(id){
        window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
        alert(id);
    }
        $(document).ready(function(){
            $("img.play-on-spotify").click(function(){
                // id = this.data();
                alert(this);
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
                    bioURL = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" + encodeURIComponent(artistName) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                    $.ajax({
                        type: "GET",
                        url: bioURL,
                            success: function (res2) {
                             console.log(res2.artist.bio.summary);
                            $("#bio").html(res2.artist.bio.summary);
                            $.ajax({
                                type: "GET",
                                url: "{{url('/')}}/api/artistalbums/" + artistID,
                                success: function (res3) {

                                    content = `<div class="col-md-12"><div class="row">`
                                    $.each(res3.items,function(i,album){
                                        image = album.images[1].url;
                                        releaseDate = new Date(album.release_date);
                                        releaseYear = releaseDate.getFullYear();
                                        content = `<div class="col-4 artist-card">
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
                                        content = content + `<br/><a href="` + album.uri + `"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a>`;
                                        content = content + `</div>
                                                `;
                                        albs.append(content);                        
                                        });
                                        if(albums.length > 19)
                                            albs.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="{{url('/')}}/albums` + query + `/2">More...</a></div>`)

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
        <div class="col-md-12 artist-info"><h3 id="artist-name-heading"></h3>
                <div class="row" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3" style="padding-bottom:0px;">
                        <h3 id="artist-name"></h3>
                        <img class="img-fluid" id="artist-image" src="/assets/images/generic-user-icon-19.jpg">
                        <p id="bio"></p>
                    </div>

                    <div class="col-md-9 col-lg-9 col-xl-9" style="background-color:#ccccff;">
                                <div class="row" id="releases">

                               </div>
                    </div>

                    <!-- </div> -->
                </div>
            </div>
@endsection
