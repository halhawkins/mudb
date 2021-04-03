<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}} - New Releases</title>
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            content = "";
            $.ajax({
                type: "GET",
                url: "/mudb/public/newreleases",
                success: function (response) {
                    ad = $("#recent-releases");
                    releases = response.albums.items;
                    $.each(releases,function(i,val){
                        image = val.images[1].url;
                        name = val.name;
                        artistarray = val.artists;
                        artists = "";
                        $.each(artistarray,function(i,artist){
                            artists = artists +`<a href="artist/` + artist.id + `">` +  artist.name + `</a>`;
                            if(artists.length > (i+1))
                                artists = artists + ", ";
                        })
                        content = `<div class="col-md-3">
                                        <div class="col-12 artist-card">
                                            <img src="` + image + `" style="width:100%;height:auto;">
                                            <h5><a href="album/` + val.id + `">` + name + `</a></h5><em>` + val.album_type + `</em><p>
                                            ` + artists +
                                        `</p></div>
                                    </div>`;
                        ad.append(content);                        
                    })

                    // $("#recent-releases").html(content);                 

                }
            });
        })
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
      <form action="search/" method="GET" class="form">

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
            <!-- <div class="col-md-12" style="background-color: white;"><h3>Recent Releases</h3>
                <div class="row" id="recent-releases" style="padding-bottom: .5em;"></div>
            </div> -->
            <div class="col-md-12 toggle-bar"><h3 id="artists-heading" class="panel-heading">Recent Releases</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="recent-releases" style="background-color: #ccccff"></div>
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