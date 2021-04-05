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
    </style>
    <script>
    function playOnSpotify(id){
        window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
        alert(id);
    }
        $(document).ready(function(){
            $("img.play-on-spotify").click(function(){
            });
            trackId = '{{$trackid}}';

            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/track/" + trackId,
                success: function (response) {
                    isrc = response.external_ids.isrc;
                    $("#artist-image").attr("src",response.album.images[0].url);
                    albumName = response.album.name;
                    albumType = response.album.album_type;
                    trackName = response.name;
                    releaseYear = new Date(response.album.release_date).getFullYear();
                    artists = "";
                    $.each(response.artists,function(i,val){
                        artists += `<a href="{{url('/')}}/artist/` + val.id + `">` + val.name + `</a>`;
                        if(response.artists.length > (i+1))
                            artists += ", ";
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{url('/')}}/api/trackvideo/" + isrc,
                        success: function (res) {
                            $.each(res,function(i,val){
                                console.log(JSON.stringify(val));
                                $("#releases").html(`
                                <div class="col-12">
                                    <div class="videoWrapper">
                                    <iframe
                                        src="https://www.youtube.com/embed/` + val.id.videoId + `">
                                    </iframe>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4>` + trackName + `</h4>
                                </div>
                                `);
                            });
                        }
                    });
                    $("#artist-image").parent().append("<h4>" + albumName + "</h4><em>" + albumType + "</em><br/>("+releaseYear+")<br/><h5>" + artists + "</h5>");
                    additionalInfo = $("#releases").parent().append(`<div class="row"></div>`);
                    additionalInfo.append("<div class='col-12'><pre>"+JSON.stringify(response,null,"\t")+"</pre></div>");
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
