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
        .artist-info{
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
            $("img.play-on-spotify").click(function(){
                // id = this.data();
                alert(this);
            });
            artistID = '{{$artistid}}';
            albs = $("#releases");
            $.ajax({
                type: "GET",
                url: "../api/artist/" + artistID,
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
                                url: "/mudb/public/artistalbums/" + artistID,
                                success: function (res3) {

                                    content = `<div class="col-md-12"><div class="row">`
                                    $.each(res3.items,function(i,album){
                                        image = album.images[1].url;
                                        releaseDate = new Date(album.release_date);
                                        releaseYear = releaseDate.getFullYear();
                                        content = `<div class="col-4 artist-card">
                                                        <img src="` + image + `" style="width:100%;height:auto;">
                                                        <h5><a title="Album name" href="album.php?albumid=` + album.id + `">` + album.name + `</a></h5>
                                                        `
                                        + "(" + releaseYear + `)<br/>
                                        `;
                                        $.each(album.artists, function(i,artguy) {
                                            content = content + `<a title="Artist name" href="artist.php?artistid=` + artguy.id + `">` + artguy.name + `</a>`;
                                            if(album.artists.length > (i+1))
                                                content = content + ", ";
                                        })
                                        content = content + `<br/><a href="` + album.uri + `"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a>`;
                                        content = content + `</div>
                                                `;
                                        albs.append(content);                        
                                        });
                                        if(albums.length > 19)
                                            albs.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="albums.php?query=` + query + `&page=2">More...</a></div>`)
                                        // albs.append(`</div></div>`);



                                    // releasesDiv = $("#releases")
                                    // albums = res3.items;
                                    // $.each(albums,function(i,val){
                                    //     releaseDate = new Date(val.release_date);
                                    //     releaseYear = releaseDate.getFullYear();
                                    //     $("#album-list").html($("#album-list").html()+ `<li><a href="` + val.external_urls.spotify + `" target="_blank"><img src="/assets/images/Spotify_play.png" class="play-on-spotify" title="Play on Spotify"/></a><a href="album.php?albumid=` + val.id + `"><img src="` + val.images[2].url + `"><div style="display:inline;"><span>`+val.name+`&nbsp;(` + releaseYear + `)&nbsp;<em>` + val.album_type + `</span></div></a></li>`);
                                    // });
                                    
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
      <form action="search.php" method="GET" class="form">

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