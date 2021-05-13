@extends('layout')

@section('script')
    <script>
        @if(Session::has('viewstyle'))
        viewstyle = "{{session('viewstyle')}}";
        @else
        viewstyle = "fat";
        @endif
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
                endPage = totalPages;
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
            console.log(startPage);

            // create an array of pages to ng-repeat in the pager control
            pages = Array.from(Array((endPage ) - (startPage-1)).keys()).map(i => startPage + i);
            // endPage--;
            ret = `<div class="w-100"><div class="d-flex justify-content-center"><ul class="pagination" style="align-self:center">
            `
            prev = startPage -1;
            if(startPage > 1){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/likedartists/` + prev + `/20">&laquo</a></i>
                    `;
            }

            $.each(pages,function(i,li){
                if(currentPage === li){
                    ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/likedartists/` + li + `/20">` + li + `</a></i>
                    `;
                }
                else{
                    ret += `    <li class="page-item"><a class="page-link" href="{{url('/')}}/likedartists/` + li + `/20">` + li + `</a></i>
                    `;
                }
            });
            next = currentPage+1;
            if(endPage < totalPages){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/likedartists/` + next + `/20">&raquo</a></i>
                    `;
            }
            ret += `</ul></div></div>`;
            return ret;
        }
        
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
            $(".dripicons-view-thumb").click(function(){
                large_view();
            });
            $(".dripicons-view-list-large").click(function(){
                compact_view();
            });
            perPage = "{{$perPage}}";
            page = "{{$page}}";
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/userlikes/artist/" + page + "/" + perPage,
                success: function (response) {
                    console.log(response);
                    artists = response.artists;
                    totalArtists = response.count;
                    ad = $("#artists");
                    $("#artist-heading").append(" (" + String((page-1)*perPage+1) + "-" + String((page-1)*20+artists.length) + " of " + totalArtists + ")");

                    $.each(artists,function(i,data){
                        artist = JSON.parse(data.item_data);
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
                    $("#artists").append(paginate(totalArtists,page,perPage,8));
                    if(viewstyle === "compact")
                        compact_view();       
                    else   
                        large_view();         
                }

                    // followers = response.followers.total;
                    // tags = response.genres;
            });
        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="tracks-heading" class="panel-heading">Liked Albums</h3></div><div class="col-6"><em class="btn float-right icon dripicons-view-thumb" title="Full Size Panel View"></em><em class="btn float-right icon dripicons-view-list-large"  title="Compact View"></em></div></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="artists"></div>
            </div>

@endsection
