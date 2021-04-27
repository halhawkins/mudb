function paginate(
    url,
    totalItems,
    currentPage,
    pageSize,
    maxPages = 10) {
    // calculate total pages
    let totalPages = Math.ceil(totalItems / pageSize);
    currentPage = parseInt(currentPage);

    // ensure current page isn't out of range
    if (currentPage < 1) {
        currentPage = 1;
    } 
    else if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    startPage = 0;
    endPage = 0;
    if (totalPages <= maxPages) {
        // total pages less than max so show all pages
        startPage = 1;
        endPage = totalPages+1;
    } 
    else {
        // total pages more than max so calculate start and end pages
        maxPagesBeforeCurrentPage = Math.floor(maxPages / 2);
        maxPagesAfterCurrentPage = Math.ceil(maxPages / 2) - 1;
        if (currentPage <= maxPagesBeforeCurrentPage) {
            // current page near the start
            startPage = 1;
            endPage = maxPages;
        } 
        else if (currentPage + maxPagesAfterCurrentPage >= totalPages) {
            // current page near the end
            startPage = totalPages - maxPages + 1;
            endPage = totalPages;
        } 
        else {
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
        ret += `    <li class="page-item"><a class="page-link active" href=` + url + prev + `/` + pageSize + `/">&laquo</a></i>
            `;
    }

    $.each(pages,function(i,li){
        if(currentPage === li){
            ret += `    <li class="page-item"><a class="page-link active" href="` + url + li + `/` + pageSize + `/">` + li + `</a></i>
            `;
        }
        else{
            ret += `    <li class="page-item"><a class="page-link" href="` + url + li + `/` + pageSize + `/">` + li + `</a></i>
            `;
        }
    });
    next = currentPage+1;
    if(endPage < totalPages){
        ret += `    <li class="page-item"><a class="page-link active" href="` + url + next + `/` + pageSize + `/">&raquo</a></i>
            `;
    }
    ret += `</ul></div></div>`;
    return ret;
}
window.app = {};
window.app.like = function(url,itemId,itemType,rating){

    $.ajax({
        type: "POST",
        url: url + "/rateitem",
        data: {
            itemid: itemId,
            type: itemType,
            rating: rating
        },
        success: function (response) {
            switch(rating){
                case 1:
                    $(".dripicons-thumbs-up").css("color","#00FF00");
                    $(".dripicons-thumbs-down").css("color","#808080");
                    break;
                case -1:
                    $(".dripicons-thumbs-down").css("color","#FF0000");
                    $(".dripicons-thumbs-up").css("color","#808080");
                    break;
                default:
                    $(".dripicons-thumbs-up").css("color","#808080");
                    $(".dripicons-thumbs-down").css("color","#808080");
                    break;
            }
        }
    });

}

function likeHandler(){
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
        like("{{url('/')}}","{{$artistid}}","artist",rating);
    });
}
