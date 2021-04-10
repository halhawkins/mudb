@extends('layout')

@section('script')
    <script>
        function msToTime(duration) {
            var milliseconds = parseInt((duration % 1000) / 100),
                seconds = Math.floor((duration / 1000) % 60),
                minutes = Math.floor((duration / (1000 * 60)) % 60),
                hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

            hours = (hours < 10) ? "0" + hours : hours;
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            seconds = (seconds < 10) ? "0" + seconds : seconds;
            if(duration >= 3600000)
                return hours + ":" + minutes + ":" + seconds;
            else
                return minutes + ":" + seconds;
        }

        $(document).ready(function(){
            albumID = '{{$albumid}}';
            $.ajax({
                type: "GET",
                url: "../api/album/" + albumID,
                success: function (response) {
                    albumName = response.name;
                    $("#album-name-div").html(albumName);
                    cr = response.copyrights;
                    copyright = "";
                    $.each(cr,function(i,val){
                        if(val.type == 'C'){
                            copyright = val.text;
                        }
                        
                    });
                    if(!copyright){
                        if(cr[0])
                            copyright = cr[0].text;
                        else
                            copyright = "";
                    }
                    $("#copyright").html(copyright);
                    artistArray = response.artists;
                    artists = "";
                    $.each(artistArray,function(i,artist){
                        artists = artists + `<a href="../artist/` + artist.id + `">` + artist.name + `</a>`;
                        if(artistArray.length > (i+1))
                            artists = artists + ", ";
                    });
                    releaseYear = new Date(response.release_date).getFullYear();
                    trackArray = response.tracks.items;
                    $.each(trackArray, function(i,track){
                        trackName = track.name;
                        explicit = track.explicit;
                        previewUrl=track.preview_url;
                        image = response.images[0].url;
                        spotifyUrl = track.uri;
                        content = `
                            <div class="col-3"> 
                                <div class="col-12 artist-card">
                                <img src="` + image + `" alt="album cover" style="width:100%;height:auto;">
                                <h5><a href="{{url('/')}}/track/` + track.id + `">` + trackName + `</a></h5>
                                <h6>` + response.name + ` (` + releaseYear + `)</h6>
                                `+ artists +`<br>
                                <a href="` + spotifyUrl + `" title="Play on spotify"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                <audio title="Audio preview" style="height:16px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>
                                </div>
                            </div>`;
                        $("#tracks").append(content)
                    });

                    albumInfoURL = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&album=" + encodeURIComponent(albumName) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                            tracks = response.tracks.items;
                            // $.each(tracks,function(i,val){
                            //     duration = val.duration_ms;
                            //     preview = val.preview_url;
                            //     if(preview === null)
                            //         $("#track-list").html($("#track-list").html()+ `<div class="col-sm-6 track-col"><div class="row"><div class="col-1"><img src="/assets/images/Spotify_play copy.svg" style="width:64px; height: 64px;"/></div><div class="col-11">` + (i+1) + "&nbsp;" + val.name+`&nbsp;` + 
                            //         `<audio src="" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>` +
                            //         `</div></div></div>`);
                            //     else
                            //     $("#track-list").html($("#track-list").html()+ `<div class="col-sm-6 track-col"><div class="row"><div class="col-1"><img src="/assets/images/Spotify_play copy.svg" style="width:64px; height: 64px;" /></div><div class="col-11">` + (i+1) + "&nbsp;" + val.name+`&nbsp;(` + msToTime(duration) + `)` + 
                            //         `<br/><em>Preview: </em><audio style="height:16px; width:250px;background-color:white; margin-left:5px;" src="` + preview + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>` +
                            //         `</div></div></div>`);
                            // });
                    }

                    // followers = response.followers.total;
                    // tags = response.genres;
            });
        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-12">
                <div class="row">
                    <div class="col-12 toggle-bar"><h3 id="album-name-div" class="panel-heading"></h3>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 secondary-bg">
                        <div class="row aux-bg1" id="tracks"></div> <!-- style="background-color: #ccccff" -->
                    </div>
                </div>
            </div>
@endsection
