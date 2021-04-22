@extends('layout')

@section('script')
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

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };

        function paginate(
            totalItems,
            currentPage,
            pageSize,
            maxPages = 10
        ) {
            // calculate total pages
            let totalPages = Math.ceil(totalItems / pageSize)+1;
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
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/top200/` + prev + `/{{$perpage}}">&laquo</a></i>
                    `;
            }

            $.each(pages,function(i,li){
                if(currentPage === li){
                    ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/top200/` + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
                else{
                    ret += `    <li class="page-item"><a class="page-link" href="{{url('/')}}/top200/` + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
            });
            next = currentPage+1;
            if(endPage < totalPages){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/top200/` + next + `/{{$perpage}}">&raquo</a></i>
                    `;
            }
            ret += `</ul></div></div>`;
            return ret;
        }

        $(document).ready(function(){
            $(".viral-menu,.new-releases-menu").removeClass("active");
            $(".top200-menu").addClass("active");
            perPage = {{$perpage}};
            page = "{{$page}}";
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/top200/" + page + "/" + perPage,
                success: function (response) {
                    trackArray = response.tracks;
                    totalTracks = response.total_count;
                    $("#tracks-heading").append(" (" + String((page-1)*perPage+1) + "-" + String((page-1)*20+response.tracks.length) + " of " + totalTracks + ")");

                    $.each(trackArray, function(i,item){
                        track = item.spotify_data;
                        position = item.position;
                        streams = item.streams;
                        console.log(track);
                        artists = "";
                        $.each(track.artists,function(i,val){
                            artists += `<a href="{{url('/')}}/artist/` + val.id + `">` + val.name + `</a>`;
                            if(track.artists.length > (i+1))
                                artists += ", ";
                        });
                        trackName = track.name;
                        explicit = track.explicit;
                        previewUrl=track.preview_url;
                        if(typeof track.album !== 'undefined'){
                            if(typeof track.album.images !== 'undefined')
                                image = track.album.images[0].url;
                            else
                                image = "{{url('/')}}/images/generic-user-icon-19.jpg";
                            releaseYear = new Date(track.album.release_date).getFullYear();
                            albumName = track.album.name
                        }
                        else{
                            image = "{{url('/')}}/images/generic-user-icon-19.jpg";
                            albumName = "";
                        }
                        spotifyUrl = track.uri;
                        // releaseYear = "";
                        burl = "{{url('/')}}";
                        content = `
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"> 
                                <div class="col-12 artist-card">
                                <h3>` + position + `</h3><a href="{{url('/')}}/track/` + track.id + `">
                                <img src="` + image + `" alt="album cover" style="width:100%;height:auto;">
                                <h5>` + trackName + `</a></h5>
                                <h6>` + albumName + ` (` + releaseYear + `)</h6>
                                `+ artists +`<br>
                                <em>Streamed ` + streams + ` times.</em><br />
                                <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                <audio title="Audio preview" style="height:16px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>
                                </div>
                            </div>`;
                        $("#tracks").append(content);
                        

                    });
                    $("#tracks").append(paginate(totalTracks,page,perPage,8));
                }

                    // followers = response.followers.total;
                    // tags = response.genres;
            });
        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><h3 id="tracks-heading" class="panel-heading">Spotify Top 200</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="tracks" style="background-color: #ccccff"></div>
            </div>

@endsection
