<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .album-list li{
            margin-bottom: .25em;
            margin-bottom: 8px;
            margin-top: 8px;
            /* border: 1px #657287 solid; */
            border: 1px rgba(101, 114, 135, .2) solid;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(255,255,255,0.78252804539784666) 0%, rgba(0,0,0,0) 100%);
        }

        .album-list li:hover{
            margin-bottom: .25em;
            margin-bottom: 8px;
            margin-top: 8px;
            /* border: 1px #657287 solid; */
            border: 1px rgba(101, 114, 135, .2) solid;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(255,255,255,0.48252804539784666) 0%, rgba(0,0,0,0.1) 100%);
        }

        .album-list li img{
            height: 48px;
            width: 48px;
            border-radius:10px;
        }

        .album-list:first-child{
            margin-top: 2em;
            margin-bottom: .25em;
            background: linear-gradient(180deg, rgba(255,255,255,0.78252804539784666) 0%, rgba(11,20,0,0) 100%);
        }

        .album-list li span {
            margin-left: 1em;
            color: #021b42;
        }
        
        .album-list.album-list li a div{
            color: #021b42;
        }

        .album-list li div {
            vertical-align: top;
            margin-left: 1em;
            display: inline;
        } 

        .play-on-spotify{
            width:24px !important;
            height:24px !important;
            margin-left: 8px;
            margin-right: 8px;
            cursor:pointer;
        }
        .artist-card{
            background-color:white;
            /* margin: 8px; */
            padding: 8px;
            /* height:95%; */
            /* width:95%; */
            display: inline-block;
            /* border: 1px solid brown; */
        }

        #artist-list{
            padding: 8px;
        }
        .track-hr{
            padding-bottom:0;
            margin-bottom: 0;
        }
        .album-subheading{
            text-align: center;
            padding-top: 0;
            font-size: .7em;
            padding-left: 5px;
            padding-right: 5px;
            margin-top: -10px;
            display: block;
        }
        .toggle-panel{
            height: 24px;
            width: 24px;
            background-image: url(/assets/images/collapse.svg); 
            background-size: cover;
            float: right;
            margin-top:8px;
            opacity: .5;
        }
        .panel-heading{
            display: inline-block;
        }
        .toggle-bar{
            color: white;
            background-color: #27889f;
            border-bottom: 1px solid #17525f;
        }
        .toggle-bar:hover{
            color: white;
            background-color: #52a0b2;
        }
        a{
            color: black;
        }
        a:hover{
            color: black;
        }
        .subalbum-name{
            font-size: 1em;
            font-weight: 500;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
                            <div class="col-3 artist-card">
                                <img src="` + image + `" alt="album cover" style="width:100%;height:auto;">
                                <h5>` + trackName + `</h5>
                                <h6>` + response.name + ` (` + releaseYear + `)</h6>
                                `+ artists +`<br>
                                <a href="` + spotifyUrl + `" title="Play on spotify"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                <audio title="Audio preview" style="height:16px; width:250px;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>

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
</head>
<body>
    <!--
        =====================================
        Start of Nav
        =====================================
    -->
    <nav class="navbar navbar-expand-md navbar-dark mb-3">
        <div class="container-fluid">
            <a href="#" class="navbar-brand mr-3" style="font-size:1.4em;"><span style="color:orange;">A</span>pp</span> <span style="color:orange;">N</span>ame</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <!-- <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Services</a>
                    <a href="#" class="nav-item nav-link">About</a>
                    <a href="#" class="nav-item nav-link">Contact</a> -->
                </div>
                <div class="navbar-nav ml-auto">
      <form action="../search" method="GET" class="form">

        <input type="search" placeholder="Search" class="search-field"  name="query"/>
        <button type="submit" class="search-button">
          <img src="/assets/images/magnifying-glass-icon-20.png">
        </button>
      </form>
                    <!-- <input type="text" class="input" placeholder="search"><button type="submit"><i class="fa fa-search"></i></button> -->
                    <a href="#" class="nav-item nav-link">Register</a>
                    <a href="#" class="nav-item nav-link">Login</a>
                </div>
            </div>
        </div>    
    </nav>
    <!--
        =====================================
        End of Nav
        =====================================
    -->
    <!--
        =====================================
        Start of Container
        =====================================
    -->
    <div class="container">
        <div class="row">
            <div class="col-12 toggle-bar"><h3 id="album-name-div" class="panel-heading"></h3>

            </div>
        </div>
        <div class="row">
            <div class="col-12" style="background-color:white;">
                <div class="row" id="tracks"></div>
            </div>
        </div>
    </div>

        <hr>
        <footer>
            <div class="row">
                <div class="col-md-6">
                    <p>Copyright &copy; 2021 Musicor</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="#" class="text-dark">Terms of Use</a> 
                    <span class="text-muted mx-2">|</span> 
                    <a href="#" class="text-dark">Privacy Policy</a>
                </div>
            </div>
        </footer>
    </div>
    <!--
        =====================================
        End of Container
        =====================================
    -->
    
</body>
</html>