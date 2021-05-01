@extends('layout')

@section('script')
<script src="{{url('/')}}/js/app.js"></script>
    <script>
        @if(Session::has('viewstyle'))
        viewstyle = "{{session('viewstyle')}}";
        @else
        viewstyle = "fat";
        @endif
function paginate(
            url,
            totalItems,
            currentPage,
            pageSize,
            maxPages = 10
        ) {
            // calculate total pages
            let totalPages = Math.ceil(totalItems / pageSize);
            currentPage = parseInt(currentPage);
            // ensure current page isn't out of range
            if (currentPage < 1) {
                currentPage = 1;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            startPage = 0;
            endPage = 0;
            if (totalPages <= maxPages) {
                // total pages less than max so show all pages
                startPage = 1;
                endPage = totalPages+1;
            } else {
                // total pages more than max so calculate start and end pages
                maxPagesBeforeCurrentPage = Math.floor(maxPages / 2);
                maxPagesAfterCurrentPage = Math.ceil(maxPages / 2) - 1;
                if (currentPage <= maxPagesBeforeCurrentPage) {
                    // current page near the start
                    startPage = 1;
                    endPage = maxPages;
                } else if (currentPage + maxPagesAfterCurrentPage >= totalPages) {
                    // current page near the end
                    startPage = totalPages - maxPages + 1;
                    endPage = totalPages;
                } else {
                    // current page somewhere in the middle
                    startPage = currentPage - maxPagesBeforeCurrentPage;
                    endPage = currentPage + maxPagesAfterCurrentPage;
                }
            }

            // calculate start and end item indexes
            startIndex = (currentPage - 1) * pageSize;
            endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);

            // create an array of pages to ng-repeat in the pager control
            pages = Array.from(Array((endPage ) - startPage).keys()).map(i => startPage + i);
            // endPage--;
            ret = `<div class="w-100"><div class="d-flex justify-content-center"><ul class="pagination" style="align-self:center">
            `
            prev = startPage -1;
            if(startPage > 1){
                ret += `    <li class="page-item"><a class="page-link active" href=` + url + prev + `/{{$perpage}}">&laquo</a></i>
                    `;
            }

            $.each(pages,function(i,li){
                if(currentPage === li){
                    ret += `    <li class="page-item"><a class="page-link active" href="` + url + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
                else{
                    ret += `    <li class="page-item"><a class="page-link" href="` + url + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
            });
            next = currentPage+1;
            if(endPage < totalPages){
                ret += `    <li class="page-item"><a class="page-link active" href="` + url + next + `/{{$perpage}}">&raquo</a></i>
                    `;
            }
            ret += `</ul></div></div>`;
            return ret;
        }

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

    function large_view(){
        $(".artist-cell").addClass("col-lg-4").addClass("col-md-6");
        $(".info-container compact").removeClass("col-9 col-sm-10 col-xl-12").addClass("col-12");
        $(".artist-card,.info-container").removeClass('compact');
        $(".artist-image").removeClass("col-3 col-sm-2 col-lg-2 col-xl-2").addClass("col-12");
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
        $(".artist-cell").removeClass("col-lg-4 col-md-6 col-sm-12").addClass("col-12");
        $(".artist-card,.info-container").addClass('compact');
        $(".info-container").removeClass("col-12").addClass("col-9 col-sm-10 col-xl-10");
        $(".artist-image").removeClass("col-12").addClass("col-3 col-sm-2 col-lg-2 col-xl-2");             
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
            $(".dripicons-view-thumb").click(function(){
                large_view();
            });
            $(".dripicons-view-list-large").click(function(){
                compact_view();
            });
            $.getScript("{{url('/')}}/js/app.js", function () {
                image = "";
                page = {{$page}};
                perPage = {{$perpage}};
                albumID = '{{$albumid}}';
                albumName = "";
                $.ajax({
                    type: "GET",
                    url: "{{url('/')}}/api/album/" + "{{$albumid}}",
                    success: function (albumResp) {
                        image = albumResp.images[0].url;
                        cr = albumResp.copyrights;
                        $(".cover-art").attr('src',image);
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
                        albumName = albumResp.name;
                        $("#album-name-div").html(albumName);
                        // coverArt = response.images[0].url;
                        artistArray = albumResp.artists;
                        artists = "";
                        textArtists = "";
                        $.each(artistArray,function(i,artist){
                            textArtists += artist.name;
                            artists += `<a href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                            if(artistArray.length > (i+1)){
                                artists += ", ";
                                textArtists += ", ";
                            }
                        });


                        // likes buttons
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $(".dripicons-thumbs-up, .dripicons-thumbs-down").click(function(){
                                if($(this).attr("class") === 'dripicons-thumbs-up'){
                                    if($(this).css("color")==="rgb(128, 128, 128)"){
                                        rating = 1;
                                    }
                                    else{
                                        rating = 0;
                                    }
                                }
                                else{
                                    if($(this).css("color")==="rgb(128, 128, 128)"){
                                        rating = -1;
                                    }
                                    else{
                                        rating = 0;
                                    }
                                }
                                app.like({
                                    url:"{{url('/')}}",
                                    itemType:'album',
                                    itemName: albumName,
                                    itemArtist: textArtists,
                                    rating: rating,
                                    itemId: '{{$albumid}}',
                                }
                                    );
                        });
                        $(".album-artists").html(artists);
                        releaseYear = new Date(albumResp.release_date).getFullYear();
                        $.ajax({
                            type: "GET",
                            url: "{{url('/')}}/rating/" + albumID + "/album",
                            success: function (likes) {
                                like = parseInt(likes.like);
                                if(like === 1){
                                    $(".dripicons-thumbs-up").css("color","#00FF00");
                                    $(".dripicons-thumbs-down").css("color","#808080");
                                }
                                else if(like === -1){
                                    $(".dripicons-thumbs-up").css("color","#808080");
                                    $(".dripicons-thumbs-down").css("color","#FF0000");
                                }
                                else{
                                    $(".dripicons-thumbs-up").css("color","#808080");
                                    $(".dripicons-thumbs-down").css("color","#808080");
                                }
                            }
                        });


                        $.ajax({
                            type: "GET",
                            url: "{{url('/')}}/api/albumtracks/" + albumID + "/" + page + "/" + perPage,
                            success: function (response) {
                                totalTracks = response.total;
                                trackArray = response.items;
                                id = response.id;
                                $.each(trackArray, function(i,track){
                                    trackName = track.name;
                                    explicit = track.explicit;
                                    previewUrl=track.preview_url;
                                    // image = response.images[0].url;
                                    spotifyUrl = track.uri;
                                    trackArtists = "";
                                    $.each(track.artists, function(i,artist){
                                        trackArtists += `<a title="Artist name" href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                                        if(track.artists.length > (i+1)){
                                            trackArtists += ", ";
                                        }
                                    });
                                    content = 
                                    `
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 artist-cell"> 

                                        <div class="col-12 artist-card compact">
                                            <div class="row">
                                                <div class="col-3 col-sm-2 col-xl-2 artist-image">
                                                    <a href="{{url('/')}}/track/` + track.id + `">
                                                    <img src="` + image + `" alt="album cover"></a>
                                                </div>
                                                <div class="col-9 col-sm-10 col-xl-10 info-container compact">
                                                    <a href="{{url('/')}}/track/` + track.id + `">
                                                    <h5 class="track-name">` + trackName + `</h5></a>
                                                            <em>`+ trackArtists +`</em><br>
                                                    <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>`
                        if(previewUrl !== null) // surpress 404s loading missing preview track
                            content +=              `<audio title="Audio preview" style="height:12px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>`;
                        content +=              `</div><!-- end info-container -->
                                            </div>
                                        </div>
                                    </div>`







                                        //     <div class="col-12 artist-card">
                                        //     <img src="` + coverArt + `" alt="album cover" style="width:100%;height:auto;">
                                        //     <h5><a href="{{url('/')}}/track/` + track.id + `">` + trackName + `</a></h5>
                                        //     <h6>` + response.name + ` (` + releaseYear + `)</h6>
                                        //     `+ artists +`<br>
                                        //     <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                        //     <audio title="Audio preview" style="height:16px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>
                                        //     </div>
                                        // </div>`;
                                    $("#tracks").append(content)
                                });

                                albumInfoURL = "https://ws.audioscrobbler.com/2.0/?method=album.getinfo&album=" + encodeURIComponent(albumName) + "&artist=" + encodeURIComponent(artistArray[0].name) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                                $.ajax({
                                    type: "GET",
                                    url: albumInfoURL,
                                    success: function (response) {
                                        if(typeof(response.album.wiki)=== 'undefined') {
                                            albumSummary = "";
                                        }
                                        else{
                                            albumSummary = response.album.wiki.summary;
                                            albumName = response.album.name;
                                            $("#album-info").append(albumSummary);
                                        }
                                    }
                                });
                                url = "{{url('/')}}/album/" + albumID + "/";
                                $("#artist").append(paginate(url,totalTracks,page,perPage,8));
                                if(viewstyle === "compact")
                        compact_view();       
                    else   
                        large_view();         
                            }

                        });

                        
            }
                });

                    
            });
                            

        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><h3 id="tracks-heading" class="panel-heading">Album</h3><em class="btn float-right icon dripicons-view-thumb" title="Full Size Panel View"></em><em class="btn float-right icon dripicons-view-list-large"  title="Compact View"></em>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12 artist-info primary-bg"><h3 id="artist-name-heading"></h3>
                <div class="row primary-bg" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3 " style="padding-bottom:0px;">
                        <h3 id="album-name-div"  class="primary-bg"></h3>
                        <img class="img-fluid cover-art" id="artist-image" src="{{url('/')}}/images/generic-user-icon-19.jpg">
                        @auth
                        <div class="m-2"><em class="dripicons-thumbs-up" title="I like this"></em>&nbsp;&nbsp;&nbsp;<em class="dripicons-thumbs-down" title="I dislike this"></em></div>
                        @endauth
                        <em class="album-artists"></em>
                        <p id="album-info"></p>
                    </div>

                    <div class="col-md-9 col-lg-9 col-xl-9 aux-bg1">
                                <div class="row aux-bg1" id="tracks">

                               </div>
                    </div>

                </div>
            </div>

@endsection
