@extends('layout')

@section('script')
<script>
        @if(Session::has('viewstyle'))
        viewstyle = "{{session('viewstyle')}}";
        @else
        viewstyle = "fat";
        @endif

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
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/newreleases/` + prev + `/{{$perpage}}">&laquo</a></i>
                    `;
            }

            $.each(pages,function(i,li){
                if(currentPage === li){
                    ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/newreleases/` + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
                else{
                    ret += `    <li class="page-item"><a class="page-link" href="{{url('/')}}/newreleases/` + li + `/{{$perpage}}">` + li + `</a></i>
                    `;
                }
            });
            next = currentPage+1;
            if(endPage < totalPages){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/newreleases/` + next + `/{{$perpage}}">&raquo</a></i>
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
            content = "";
            $(".viral-menu,.top200-menu, .recommendations-menu").removeClass("active");
            $(".new-releases-menu").addClass("active");
            page = {{$page}};
            perPage = {{$perpage}};
            $.ajax({
                type: "GET", 
                url: "{{url('/')}}/api/newreleases/" + page + "/" + perPage,
                success: function (response) { 
                    ad = $("#recent-releases");
                    releases = response.albums.items;
                    totalTracks = response.albums.total;
                    $.each(releases,function(i,val){
                        image = val.images[1].url;
                        trackName = val.name;
                        artistarray = val.artists;
                        artists = "";
                        spotifyUrl = val.uri;
                        $.each(artistarray,function(i,artist){
                            artists = artists +`<a href="{{url('/')}}/artist/` + artist.id + `">` +  artist.name + `</a>`;
                            if(artists.length > (i+1))
                                artists = artists + ", ";
                        })
                        // content = `<div class="col-md-3">
                        //                 <div class="col-12 artist-card">
                        //                     <img src="` + image + `" style="width:100%;height:auto;">
                        //                     <h5><a href="{{url('/')}}/album/` + val.id + `">` + name + `</a></h5><em>` + val.album_type + `</em><p>
                        //                     ` + artists +
                        //                 `</p></div>
                        //             </div>`;
                        content = `
                            <div class="col-lg-3 col-md-4 col-sm-12 col-12 artist-cell"> 
                                <div class="col-12 artist-card compact">
                                    <div class="row">
                                        <div class="col-3 col-sm-2 col-xl-1 artist-image">
                                            <a href="{{url('/')}}/track/` + val.id + `">
                                                <img src="` + image + `" alt="album cover">
                                            </a>
                                        </div>
                                        <div class="col-9 col-sm-10 col-xl-11 info-container compact">
                                            <a href="{{url('/')}}/track/` + val.id + `">
                                                <h5>` + trackName + `</h5>
                                            </a>
                                                
                                            <em>`+ artists +`</em><br>
                                            <a href="` + spotifyUrl + `" title="Play on spotify"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
                                        </div><!-- end info-container -->
                                    </div>
                                </div>
                            </div>`;
                        ad.append(content);                        
                    })
                    $("#recent-releases").append(paginate(totalTracks,page,perPage,8));  
                    if(viewstyle === "compact")
                        compact_view();       
                    else   
                        large_view();         
                }
            });
        })
    </script>
@endsection

@section('mainbody')
<div class="col-md-12 toggle-bar"><div class="row"><div class="col-6 pt-1"><h3 id="artists-heading" class="panel-heading">Recent Releases</h3></div><div class="col-6"><em class="btn float-right icon dripicons-view-thumb" title="Full Size Panel View"></em><em class="btn float-right icon dripicons-view-list-large"  title="Compact View"></em></div></div>
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="recent-releases"></div> <!--style="background-color: #ccccff"-->
            </div>

@endsection