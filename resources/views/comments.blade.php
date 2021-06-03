@section('commentscript')
<script>
@if(isset($artistid))
const itemId = "{{$artistid}}";
@elseif(isset($albumid))
const itemId = "{{$albumid}}";
@elseif(isset($trackid))
const itemId = "{{$trackid}}";
@endif

function showComments(comments,list){
    if(Array.isArray(comments)){
        ul = list.html("<ul></ul>");
        $.each(comments,function(i,val)){
            showComments(val,ul);
        }
    }
    else{
        list.html(`<img src="`+comments.user.avatar+`" alt="user avatar"><strong>` + comments.user.avatar+`</strong> - ` + comments.comment_body);
    }
    return list;
}

$(document).ready(function(){
    $.ajax({
        type: "get",
        url: "{{url('/')}}/comments/" + itemId,
        success: function (response) {
            $(".commentdiv").html(showComments(response));
        }
    });
});
</script>
@endsecion
@section('comments')
<div class="col-12 commentdiv">
</div>
@endsection