@extends('layout')

@section('script')
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
                url: "{{url('/')}}/api/searchall/" + query,
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
                            image = "{{url('/')}}/images/noartistimage.png";
                        }
                        else{
                            image = artist.images[2].url;
                        }
                        content = `<div class="col-md-3">
                                        <div class="col-12 artist-card">
                                            <img src="` + image + `" style="width:100%;height:auto;">
                                            <h5><a title="Artist name" href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a></h5><em>
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
                            ad.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="{{url('/')}}/artists/` + query + `/2">More...</a></div>`)
                    // albums
                    //
                    $.each(albums,function(i,album){
                        image = album.images[1].url;
                        releaseDate = new Date(album.release_date);
                        releaseYear = releaseDate.getFullYear();
                        content = `<div class="col-md-3">
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
                                    content = content + `</div>
                                </div>`;
                        albs.append(content);                        
                        });
                        if(albums.length > 19)
                            albs.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="{{url('/')}}/albums/` + query + `/2">More...</a></div>`)
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
                                        <h5><a title="Track name" href="{{url('/')}}/track/` + track.id + `">` + track.name + `</a></h5>`
                                        + "(" + releaseYear + `)<br/>
                                        `;
                        $.each(track.artists, function(i,artist){
                            content = content + `<a title="Artist name" href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                            if(track.artists.length > (i+1)){
                                content = content+", ";
                            }
                        });
                        content = content + `
                                        <br/><a title="Album name" class="subalbum-name" href="{{url('/')}}/album/` + track.album.id + `">` + track.album.name + `</a>
                                        </div>
                                </div>`;
                        trks.append(content);                        
                        });
                        if(tracks.length > 19)
                            trks.append(`<div class="col-md-12"><a style="float:right; font-size:1.3em;" href="{{url('/')}}/tracks/` + query + `/2">More...</a></div>`)
                    }
                })
            });
        
    </script>
@endsection

@section('mainbody')
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
@endsection
