@extends('layout')

@section('script')
    <script>
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

        $(document).ready(function(){
            page = {{$page}};
            perPage = {{$perpage}};
            albumID = '{{$albumid}}';
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/album/" + "{{$albumid}}",
                success: function (albumResp) {
                    coverArt = albumResp.images[0].url;
                    cr = albumResp.copyrights;
                    $(".cover-art").attr('src',coverArt);
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
                    $.each(artistArray,function(i,artist){
                        artists = artists + `<a href="{{url('/')}}/artist/` + artist.id + `">` + artist.name + `</a>`;
                        if(artistArray.length > (i+1))
                            artists = artists + ", ";
                    });
                    releaseYear = new Date(albumResp.release_date).getFullYear();


                    $.ajax({
                        type: "GET",
                        url: "{{url('/')}}/api/albumtracks/" + albumID + "/" + page + "/" + perPage,
                        success: function (response) {
                            // console.log(response);
                            totalTracks = response.total;
                            trackArray = response.items;
                            id = response.id;
                            $.each(trackArray, function(i,track){
                                trackName = track.name;
                                explicit = track.explicit;
                                previewUrl=track.preview_url;
                                // image = response.images[0].url;
                                spotifyUrl = track.uri;
                                content = `
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                        <div class="col-12 artist-card">
                                        <img src="` + coverArt + `" alt="album cover" style="width:100%;height:auto;">
                                        <h5><a href="{{url('/')}}/track/` + track.id + `">` + trackName + `</a></h5>
                                        <h6>` + response.name + ` (` + releaseYear + `)</h6>
                                        `+ artists +`<br>
                                        <a href="` + spotifyUrl + `" title="Play on spotify"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                        <audio title="Audio preview" style="height:16px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>
                                        </div>
                                    </div>`;
                                $("#tracks").append(content)
                            });

                            albumInfoURL = "https://ws.audioscrobbler.com/2.0/?method=album.getinfo&album=" + encodeURIComponent(albumName) + "&artist=" + encodeURIComponent(artistArray[0].name) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                            $.ajax({
                                type: "GET",
                                url: albumInfoURL,
                                success: function (response) {
                                    if(typeof(response.album)=== 'undefined') {
                                        albumSummary = "";
                                        alert("woops");
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
                        }

                    });

                    
        }
            });


        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 artist-info primary-bg"><h3 id="artist-name-heading"></h3>
                <div class="row primary-bg" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3 " style="padding-bottom:0px;">
                        <h3 id="album-name-div"  class="primary-bg"></h3>
                        <img class="img-fluid cover-art" id="artist-image" src="{{url('/')}}/images/generic-user-icon-19.jpg">
                        <p id="album-info"></p>
                    </div>

                    <div class="col-md-9 col-lg-9 col-xl-9 aux-bg1">
                                <div class="row aux-bg1" id="tracks">

                               </div>
                    </div>

                    <!-- </div> -->
                </div>
            </div>



            <!-- <div class="col-12">
                <div class="row">
                    <div class="col-12 toggle-bar"><h3 id="album-name-div" class="panel-heading"></h3>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 secondary-bg">
                        <div class="row aux-bg1" id="tracks"></div>
                    </div>
                </div>
            </div> -->
@endsection
