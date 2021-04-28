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
            $(".dripicons-view-thumb").click(function(){
                $(".artist-card,.info-container").removeClass('compact');
                $(".info-container compact").removeClass("col-10").addClass("col-12");
                $(".artist-image").removeClass("col-2").addClass("col-12");
            });
            $(".dripicons-view-list-large").click(function(){
                $(".artist-card,.info-container").addClass('compact');
                $(".info-container").removeClass("col-12").addClass("col-10");
                $(".artist-image").removeClass("col-12").addClass("col-2");
            });
            $(".viral-menu,.top200-menu,.new-releases-menu").removeClass("active");
            $(".recommendations-menu").addClass("active");
            perPage = {{$perpage}};
            page = 1;
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/personal",
                success: function (response) {
                    trackArray = response.tracks;
                    totalTracks = response.total_count;
                    // $("#tracks-heading").append(" (" + String((page-1)*perPage+1) + "-" + String((page-1)*20+response.tracks.length) + " of " + totalTracks + ") for " + new Date(response.top200_for_date).toLocaleDateString());

                    $.each(trackArray, function(i,item){
                        track = item;//.spotify_data
                        position = item.position;
                        streams = item.streams;
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
                                <div class="col-12 artist-card compact">
                                    <div class="row">
                                            <div class="col-2 artist-image">
                                                <a href="{{url('/')}}/track/` + track.id + `">
                                                <img src="` + image + `" alt="album cover"></a>
                                            </div>
                                            <div class="col-10 info-container compact">
                                                    <h5>` + trackName + `</h5>
                                                    <span class="">` + albumName + ` (` + releaseYear + `)</span>
                                                            <em>`+ artists +`</em><br>
                                                    <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                                    <audio title="Audio preview" style="height:12px; width:90%;background-color:white; margin-left:5px;" src="` + previewUrl + `" type="audio/mpeg" controls disabled>I'm sorry. You're browser doesn't support HTML5 <code>audio</code>.</audio>
                                            </div><!-- end info-container -->
                                    </div>
                                </div>
                            </div>`;
                        $("#tracks").append(content);
                        

                    });
                    $("#tracks").append(`<div class="w-100"><div class="d-flex justify-content-center"><a class="page-link" href="{{url('/')}}/recommendations">More...</a></div>`);
                }

                    // followers = response.followers.total;
                    // tags = response.genres;
            });
        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><h3 id="tracks-heading" class="panel-heading">Recommendations</h3><em class="btn float-right icon dripicons-view-thumb"></em><em class="btn float-right icon dripicons-view-list-large"></em>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="tracks" style="background-color: #ccccff"></div>
            </div>

@endsection
