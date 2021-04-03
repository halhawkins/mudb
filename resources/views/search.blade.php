<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        /* body{
            background: rgb(29, 7, 0);
        }

        #recent-releases{
            color: rgb(173, 145, 113);
        }

        .artist-jumbo{
            background-color: ;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            margin-bottom: 8px;
            margin-top: 2em;
        }

        h3{
            padding-bottom: 1.5em;
        }*/

        .artist-jumbo{
            margin-bottom: 8px;
            margin-top: 2em;
        }

`````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````        /* .album-list{
            list-style: none;
            padding: 10px;
        } */
`````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````````
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
            margin: 8px;
            padding: 8px;
            height:95%;
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
    <script>
    function playOnSpotify(id){
        window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
        alert(id);
    }
        $(document).ready(function(){
            $(".toggle-bar").click(function(){
                if($(this).next().is(":visible"))
                    $(this).next().hide();
                else
                    $(this).next().show();
            });
            query = '{{$query}}';
            $.ajax({
                type: "GET",
                url: "../api/searchall/" + query,
                success: function (response) {
                    artists = response.artists.items;
                    albums = response.albums.items;
                    tracks = response.tracks.items;
                    ad = $("#artist-list");
                    albs = $("#album-list");
                    trks = $("#track-list");
                    // artists
                    // 
                    $.each(artists,function(i,artist){
                        if(artist.images.length === 0){
                            image = "/assets/images/noartistimage.png";
                        }
                        else{
                            image = artist.images[2].url;
                        }
                        content = `<div class="col-md-3">
                                        <div class="col-12 artist-card">
                                            <img src="` + image + `" style="width:100%;height:auto;">
                                            <h5><a title="Artist name" href="../artist/` + artist.id + `">` + artist.name + `</a></h5><em>
                                            `
                        if(artist.genres.length > 4)
                            numGenres = 4;
                        else
                            numGenres = artist.genres.length;
                        genres = artist.genres.slice(0,numGenres);
                        $.each(genres, function(i,genre){
                            content = content + genre;
                            if(numGenres > (i+1)){
                                content = content+", ";
                            }
                        });
                                        `</em></div>
                                    </div>`;
                        ad.append(content);                        
                        });
                        if(artists.length > 19)
                            ad.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="artists.php?query=` + query + `&page=2">More...</a></div>`)
                    // albums
                    //
                    $.each(albums,function(i,album){
                        image = album.images[1].url;
                        releaseDate = new Date(album.release_date);
                        releaseYear = releaseDate.getFullYear();
                        content = `<div class="col-md-3">
                                    <div class="col-12 artist-card">
                                        <img src="` + image + `" style="width:100%;height:auto;">
                                        <h5><a title="Album name" href="../album/` + album.id + `">` + album.name + `</a></h5>
                                        `
                        + "(" + releaseYear + `)<br/>
                        `;
                        $.each(album.artists, function(i,artguy) {
                            content = content + `<a title="Artist name" href="../artist/` + artguy.id + `">` + artguy.name + `</a>`;
                            if(album.artists.length > (i+1))
                                content = content + ", ";
                        })
                                    content = content + `</div>
                                </div>`;
                        albs.append(content);                        
                        });
                        if(albums.length > 19)
                            albs.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="albums.php?query=` + query + `&page=2">More...</a></div>`)
                    // tracks
                    //
                    $.each(tracks,function(i,track){
                        album = track.album;
                        image = album.images[1].url;
                        releaseDate = new Date(album.release_date);
                        releaseYear = releaseDate.getFullYear();
                        content = `<div class="col-md-3">
                                    <div class="col-12 artist-card">
                                        <img src="` + image + `" style="width:100%;height:auto;">
                                        <h5><a title="Track name" href="album.php?albumid=` + track.id + `">` + track.name + `</a></h5>`
                                        + "(" + releaseYear + `)<br/>
                                        `;
                        $.each(track.artists, function(i,artist){
                            content = content + `<a title="Artist name" href="../artist/` + artist.id + `">` + artist.name + `</a>`;
                            if(track.artists.length > (i+1)){
                                content = content+", ";
                            }
                        });
                        content = content + `
                                        <br/><a title="Album name" class="subalbum-name" href="../album/` + track.album.id + `">` + track.album.name + `</a>
                                        </div>
                                </div>`;
                        trks.append(content);                        
                        });
                        if(tracks.length > 19)
                            trks.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="../artists/` + query + `/2">More...</a></div>`)
                    }
                })
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
      <form action="../search" method="POST" class="form">

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
            <div class="col-md-12 toggle-bar"><h3 id="artists-heading" class="panel-heading">Artists</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="artist-list" style="background-color: #ccccff"></div>
            </div>
            <div class="col-md-12 toggle-bar"><h3 id="artists-heading" class="panel-heading">Albums</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="album-list" style="background-color: #ccccff"></div>
            </div>
            <div class="col-md-12 toggle-bar"><h3 id="tracks-heading" class="panel-heading">Tracks</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="track-list" style="background-color: #ccccff"></div>
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