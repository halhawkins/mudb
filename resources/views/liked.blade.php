@extends('layout')

@section('script')
    <script>
        @if(Session::has('viewstyle'))
        viewstyle = "{{session('viewstyle')}}";
        @else
        viewstyle = "fat";
        @endif
    function large_view(){
        $(".artist-cell").addClass("col-lg-3").addClass("col-md-4");
        $(".artist-card,.info-container").removeClass('compact');
        $(".info-container compact").removeClass("col-9 col-sm-10 col-xl-11").addClass("col-12");
        $(".artist-image").removeClass("col-3 col-sm-2 col-xl-1").addClass("col-12");
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
            $(".artist-cell").removeClass("col-lg-3").removeClass("col-md-4");
            $(".artist-card,.info-container").addClass('compact');
            $(".info-container").removeClass("col-12").addClass("col-9 col-sm-10 col-xl-11");
            $(".artist-image").removeClass("col-12").addClass("col-3 col-sm-2 col-xl-1");             
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
            $(".toggle-bar").click(function(){
                if($(this).next().children(".aux-bg1").is(":visible")){
                    $(this).next().children(".aux-bg1").hide();
                }
                else{
                    $(this).next().children(".aux-bg1").show();
                }
            });
            $(".dripicons-view-thumb").click(function(e){
                e.stopPropagation(); // prevents .toggle-bar handler from collapsing the view
                large_view();
            });
            $(".dripicons-view-list-large").click(function(e){
                e.stopPropagation(); // prevents .toggle-bar handler from collapsing the view
                compact_view();
            });
            $(".expand").click(function(){
                if($(this).parent().next().is(":visible")){
                    $(this).parent().next().hide();
                    $(this).removeClass('dripicons-contract-2').addClass('dripicons-expand-2');
                }
                else{
                    $(this).parent().next().show();
                    $(this).removeClass('dripicons-expand-2').addClass('dripicons-contract-2');
                }
            });
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/userlikes/all",
                success: function (response) {
                    artists = response[0].artists;
                    albums = response[1].albums;
                    tracks = response[2].tracks;
                    ad = $("#artist-list");
                    albs = $("#album-list");
                    trks = $("#track-list");

                    // artists
                    // 
                    $.each(artists,function(i,adata){
                        artist = JSON.parse(adata.item_data);
                        console.log(artist);
                        // console.log(albums);
                        // console.log(tracks);
                        if(artist.images.length === 0){
                            image = "{{url('/')}}/images/noartistimage.png";
                        }
                        else{
                            image = artist.images[2].url;
                        }
                        artistId = artist.id;
                        artistName = artist.name;
                        spotifyUrl = artist.uri;

                        if(artist.genres.length > 4)
                            numGenres = 4;
                        else
                            numGenres = artist.genres.length;
                        genres = artist.genres.slice(0,numGenres);
                        genrelist = "";
                        $.each(genres, function(i,genre){
                            genrelist += genre;
                            if(numGenres > (i+1)){
                                genrelist += ", ";
                            }
                        });

                        content =
                                `<div class="col-lg-3 col-md-4 col-sm-12 col-12 artist-cell"> 
                                <div class="col-12 artist-card compact">
                                    <div class="row">
                                            <div class="col-3 col-sm-2 col-xl-1 artist-image">
                                                <a href="{{url('/')}}/artist/` + artistId+ `">
                                                <img src="` + image + `" alt="album cover"></a>
                                            </div>
                                            <div class="col-9 col-sm-10 col-xl-11 info-container compact">
                                                <a href="{{url('/')}}/artist/` + artistId+ `">
                                                    <h5>` + artistName + `</h5>
                                                </a>
                                                <em>`+genrelist+`</em><br>
                                                <a href="` + spotifyUrl + `" title="View on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> View on Spotify</a><br/>
                                    </div>
                                </div>
                            </div>`;


                        ad.append(content);                        
                        });
                        if(artists.length > 19)
                            ad.append(`<div class="w-100"><div class="d-flex justify-content-center"><a class="page-link" href="{{url('/')}}/likedartists/1/20">More...</a></div></div>`)
                    // albums
                    //
                    // alert(query);
                    $.each(albums,function(i,data){
                        album = JSON.parse(data.item_data);
                        image = album.images[1].url;
                        releaseDate = new Date(album.release_date);
                        releaseYear = releaseDate.getFullYear();
                        artists = "";
                        albumName = album.name;
                        spotifyUrl = album.uri;
                        $.each(album.artists, function(i,artguy) {
                            artists += `<a title="Artist name" href="{{url('/')}}/artist/` + artguy.id + `">` + artguy.name + `</a>`;
                            if(album.artists.length > (i+1))
                            artists += ", ";
                        })
                        
                        content =
                                `<div class="col-lg-3 col-md-4 col-sm-12 col-12 artist-cell"> 
                                    <div class="col-12 artist-card compact">
                                        <div class="row">
                                            <div class="col-3 col-sm-2 col-xl-1 artist-image">
                                                <a href="{{url('/')}}/album/` + album.id+ `">
                                                <img src="` + image + `" alt="album cover"></a>
                                            </div>
                                            <div class="col-9 col-sm-10 col-xl-11 info-container compact">
                                                <a href="{{url('/')}}/album/` + album.id+ `">
                                                    <h5>` + albumName + ` (` + releaseYear + `)</h5>
                                                </a>
                                                <em>`+ artists +`</em><br>
                                                <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                        albs.append(content);                        
                        });
                        if(albums.length > 19)
                            albs.append(`<div class="w-100"><div class="d-flex justify-content-center"><a class="page-link" href="{{url('/')}}/likedalbums/1/20">More...</a></div></div>`)
                    // tracks
                    //
                    $.each(tracks,function(i,data){
                        track = JSON.parse(data.item_data);
                        album = track.album;
                        image = album.images[1].url;
                        releaseDate = new Date(album.release_date);
                        releaseYear = releaseDate.getFullYear();
                        trackArtists = "";
                        albumName = album.name;
                        albumId = album.id;
                        trackName = track.name;
                        spotifyUrl = track.uri;
                        previewUrl = track.preview_url;
                        $.each(track.artists, function(i,artist){
                            trackArtists += `<a title="Artist name" href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                            if(track.artists.length > (i+1)){
                                trackArtists += ", ";
                            }
                        });

                        content = 
                        
                            `<div class="col-lg-3 col-md-4 col-sm-12 col-12 artist-cell"> 
                                <div class="col-12 artist-card compact">
                                    <div class="row">
                                        <div class="col-3 col-sm-2 col-xl-1 artist-image">
                                            <a href="{{url('/')}}/track/` + track.id + `">
                                                <img src="` + image + `" alt="album cover">
                                            </a>
                                        </div>
                                        <div class="col-9 col-sm-10 col-xl-11 info-container compact">
                                            <a href="{{url('/')}}/track/` + track.id + `">
                                                <h5>` + trackName + `</h5>
                                            </a>
                                            <span class=""><a href="{{url('/')}}/album/` + albumId + `">` + albumName + ` (` + releaseYear + `)</a></span><br/>
                                            <em>`+ trackArtists +`</em><br>
                                            <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>`
                        if(previewUrl !== null) // surpress 404s loading missing preview track
                            content +=         `<audio title="Audio preview" style="height:12px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>`;
                        content +=      `</div><!-- end info-container -->
                                    </div>
                                </div>
                            </div>
                                `;


                        trks.append(content);                        
                        });
                        if(tracks.length > 19)
                            trks.append(`<div class="w-100"><div class="d-flex justify-content-center"><a class="page-link" href="{{url('/')}}/likedtracks/1/20">More...</a></div></div>`)
                            if(viewstyle === "compact")
                        compact_view();       
                    else   
                        large_view();         
                    }
                })
            });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="artists-heading" class="panel-heading">Artists</h3></div><div class="col-6"><em class="btn float-right icon dripicons-contract-2 expand" title="Contract Panel"></em><em class="btn float-right icon dripicons-view-thumb" title="Full Size Panel View"></em><em class="btn float-right icon dripicons-view-list-large"  title="Compact View"></em></div></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="artist-list"></div>
            </div>
            <div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="artists-heading" class="panel-heading">Albums</h3></div><div class="col-6"><em class="btn float-right icon dripicons-contract-2 expand" title="Contract Panel"></em></div></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="album-list"></div>
            </div>
            <div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="tracks-heading" class="panel-heading">Tracks</h3></div><div class="col-6"><em class="btn float-right icon dripicons-contract-2 expand" title="Contract Panel"></em></div></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="track-list"></div>
            </div>
@endsection