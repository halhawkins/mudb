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

    function playOnSpotify(id){
        window.open("https://api.spotify.com/v1/albums/" + id, "_blank");
        alert(id);
    }
        $(document).ready(function(){
            $("img.play-on-spotify").click(function(){
                // id = this.data();
                alert(this);
            });
            artistID = '{{$artistid}}';
            albs = $("#releases");
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/artist/" + artistID,
                success: function (response) {
                    $("#artist-image").attr("src",response.images[0].url);
                    backgroundimage = "linear-gradient(to bottom, rgba(245, 246, 252, 0.22), rgba(255, 255, 255, 1)), url(" + response.images[0].url + ")";
                    artistName = response.name;
                    $(".artist-jumbo").css("background-image",backgroundimage);
                    $(".artist-jumbo").css("background-size","cover");
                    $("#artist-name-heading").html(artistName);
                    bioURL = "https://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=" + encodeURIComponent(artistName) + "&api_key=40e7023497e3403fc3d672679eba6f03&format=json";
                    $.ajax({
                        type: "GET",
                        url: bioURL,
                            success: function (res2) {
                             console.log(res2.artist.bio.summary);
                            page = {{$page}};
                            perPage = {{$perpage}};
                            $("#bio").html(res2.artist.bio.summary);
                            $.ajax({
                                type: "GET",
                                url: "{{url('/')}}/api/artistalbums/" + artistID + "/" + page + "/" + perPage,
                                success: function (res3) {
                                    totalAlbums = res3.total;

                                    content = `<div class="col-md-12"><div class="row">`
                                    $.each(res3.items,function(i,album){
                                        image = album.images[1].url;
                                        releaseDate = new Date(album.release_date);
                                        releaseYear = releaseDate.getFullYear();
                                        content = `<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
                                        content = content + `<br/><a href="` + album.uri + `"><img src="{{url('/')}}/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a>`;
                                        content = content + `</div>
                                                            </div>
                                                `;
                                        albs.append(content);                        
                                        });
                                        url = "{{url('/')}}/artist/" + artistID + "/";
                                        $("#artist").append(paginate(url,totalAlbums,page,perPage,8)); 

                                }
                            });
                        },
                        fail:function(){
                            console.log("fail");
                        }
                    });
                    followers = response.followers.total;
                    tags = response.genres;
                    }
                })
            });
        
    </script>
@endsection

@section('mainbody')
        <div class="col-md-12 artist-info primary-bg"><h3 id="artist-name-heading"></h3>
                <div class="row primary-bg" id="artist" style="padding-bottom:0px;">
                    <div class="col-md-3 col-lg-3 col-xl-3 " style="padding-bottom:0px;">
                        <h3 id="artist-name  primary-bg"></h3>
                        <img class="img-fluid" id="artist-image" src="/assets/images/generic-user-icon-19.jpg">
                        <p id="bio"></p>
                    </div>

                    <div class="col-md-9 col-lg-9 col-xl-9 aux-bg1">
                                <div class="row aux-bg1" id="releases">

                               </div>
                    </div>

                    <!-- </div> -->
                </div>
            </div>
@endsection
