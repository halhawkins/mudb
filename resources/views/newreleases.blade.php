@extends('layout')

@section('script')
<script>
        $(document).ready(function(){
            content = "";
            $.ajax({
                type: "GET", 
                url: "{{url('/')}}/api/newreleases",
                success: function (response) {
                    ad = $("#recent-releases");
                    releases = response.albums.items;
                    $.each(releases,function(i,val){
                        image = val.images[1].url;
                        name = val.name;
                        artistarray = val.artists;
                        artists = "";
                        $.each(artistarray,function(i,artist){
                            artists = artists +`<a href="{{url('/')}}/artist/` + artist.id + `">` +  artist.name + `</a>`;
                            if(artists.length > (i+1))
                                artists = artists + ", ";
                        })
                        content = `<div class="col-md-3">
                                        <div class="col-12 artist-card">
                                            <img src="` + image + `" style="width:100%;height:auto;">
                                            <h5><a href="{{url('/')}}/album/` + val.id + `">` + name + `</a></h5><em>` + val.album_type + `</em><p>
                                            ` + artists +
                                        `</p></div>
                                    </div>`;
                        ad.append(content);                        
                    })
                }
            });
        })
    </script>
@endsection

@section('mainbody')
<div class="col-md-12 toggle-bar"><h3 id="artists-heading" class="panel-heading">Recent Releases</h3><div class="toggle-panel"></div>
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="recent-releases"></div> <!--style="background-color: #ccccff"-->
            </div>

@endsection