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

            // create an array of pages to ng-repeat in the pager control
            pages = Array.from(Array((endPage ) - startPage).keys()).map(i => startPage + i);
            console.log(
                {
                totalItems: totalItems,
                currentPage: currentPage,
                pageSize: pageSize,
                totalPages: totalPages,
                startPage: startPage,
                endPage: endPage,
                startIndex: startIndex,
                endIndex: endIndex,
                pages: pages
            }

            );
            // endPage--;
            ret = `<div class="w-100"><div class="d-flex justify-content-center"><ul class="pagination" style="align-self:center">
            `
            prev = startPage -1;
            if(startPage > 1){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/tracks/{{$query}}/` + prev + `">&laquo</a></i>
                    `;
            }

            $.each(pages,function(i,li){
                if(currentPage === li){
                    ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/tracks/{{$query}}/` + li + `">` + li + `</a></i>
                    `;
                }
                else{
                    ret += `    <li class="page-item"><a class="page-link" href="{{url('/')}}/tracks/{{$query}}/` + li + `">` + li + `</a></i>
                    `;
                }
            });
            next = pages[pages.length-1]+1
            if(endPage < totalPages){
                ret += `    <li class="page-item"><a class="page-link active" href="{{url('/')}}/tracks/{{$query}}/` + next + `">&raquo</a></i>
                    `;
            }
            ret += `</ul></div></div>`;
            return ret;

            // return object with all pager properties required by the view
            // {
            //     totalItems: totalItems,
            //     currentPage: currentPage,
            //     pageSize: pageSize,
            //     totalPages: totalPages,
            //     startPage: startPage,
            //     endPage: endPage,
            //     startIndex: startIndex,
            //     endIndex: endIndex,
            //     pages: pages
            // };
        }

        function oldpaginate(total,perPage,page){
            lastPage = Math.floor(total/perPage);
            if(page>=6 && total >= 10){
                start = page-5;
                content = `
                    <ul id="pagenav">
                        <li><a href="{{url('/')}}/tracks/{{$query}}/` + start-1 + `">&laquo</a></li>`;
                for(i = start; i < start+10; i++){
                    content += `        
                            <li><a href="{{url('/')}}/tracks/{{$query}}/` + i + `">` + i + `</a></li>
                            `;
                    end = i +1;
                }
                content += `
                        <li><a href="{{url('/')}}/tracks/{{$query}}/` + end + `">&laquo</a></li></ul>`;
                
            }
            else if(page <6 && total >= 10){
                start = 1;
                content = `
                    <ul id="pagenav">
                        <li><a href="{{url('/')}}/tracks/{{$query}}/` + start-1 + `">&laquo</a></li>`;
                for(i = start; i < start+10; i++){
                    content += `        
                            <li><a href="{{url('/')}}/tracks/{{$query}}/` + i + `">` + i + `</a></li>
                            `;
                    end = i +1;
                }
                content += `
                        <li><a href="{{url('/')}}/tracks/{{$query}}/` + end + `">&laquo</a></li></ul>`;
            }
            return content;        
        }

        $(document).ready(function(){
            perPage = 20;
            query = "{{$query}}";
            page = "{{$page}}";
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/api/gettracks/" + query + "/" + page,
                success: function (response) {
                    trackArray = response.tracks.items;
                    totalTracks = response.tracks.total;
                    $("#tracks-heading").append(" (" + String((page-1)*perPage+1) + "-" + String((page-1)*20+response.tracks.items.length) + " of " + totalTracks + ")");

                    $.each(trackArray, function(i,track){
                        artists = "";
                        $.each(track.artists,function(i,val){
                            artists += `<a href="{{url('/')}}/artist/` + val.id + `">` + val.name + `</a>`;
                            if(track.artists.length > (i+1))
                                artists += ", ";
                        });
                        trackName = track.name;
                        explicit = track.explicit;
                        previewUrl=track.preview_url;
                        image = track.album.images[0].url;
                        spotifyUrl = track.uri;
                        releaseYear = new Date(track.album.release_date).getFullYear();
                        burl = "{{url('/')}}";
                        content = `
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"> 
                                <div class="col-12 artist-card">
                                <img src="` + image + `" alt="album cover" style="width:100%;height:auto;">
                                <h5><a href="{{url('/')}}/track/` + track.id + `">` + trackName + `</a></h5>
                                <h6>` + track.album.name + ` (` + releaseYear + `)</h6>
                                `+ artists +`<br>
                                <a href="` + spotifyUrl + `" title="Play on spotify"><img src="/assets/images/Spotify_play.png" style="width:24px;height:auto;"> Play on Spotify</a><br/>
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
            <div class="col-md-12 toggle-bar"><h3 id="tracks-heading" class="panel-heading">Tracks</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row" id="tracks" style="background-color: #ccccff"></div>
            </div>

@endsection