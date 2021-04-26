@extends('layout')

@section('script')
    <style>
div.img-container {
    /* position: relative; */
    /* float:left; */
    /* margin:5px; */
    padding: 5em;
}
div.img-container:hover img{
    /* opacity:0.5; */
}
div.img-container:hover div {
    display: block;
}
div.img-container .update {
    position:absolute;
    display:none;
    top:5em;
    left:5em;
}
div.img-container .update {
    top:0;
    left: 0;
    top:5em;
    left:5em;
    background-color: lightgrey;
}

div.update img{
    /* top:0;
    left: 0; */
    height:32px;
    width:32px;
}

.genre-list-item{
    cursor: pointer;
    width: 100%;
    background-color: #ededed;
    border: 1px solid #cccccc;
    border-radius: 4px;
    padding-left:12px;
    padding-right:12px;
    padding-top:3px;
    padding-bottom: 3px;
    margin: 4px;
}
.genre-list-item:hover{
    background-color: #d9d9d9;
}

    </style>
    <link href="{{url('/')}}/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
    <script src="{{url('/')}}/js/bootstrap-tokenfield.js"></script>
    <script>

        let allGenre;
        function findTagId(name){
            id = "";
            $.each(allGenre,function(i,val){
                if(val.name===name){
                    id = val.id;
                }
            });
            return id;
        }

        function findTagName(id){
            name = "";
            $.each(allGenre,function(i,val){
                if(val.id===id){
                    name = val.name;
                }
            });
            return name;
        }

        function saveTag(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{url('/')}}/rateitem",
                data: {
                    itemid: id,
                    type: "tag",
                    rating: 1
                }
            });
        }

        function clearLikes(){
            $.ajax({
                    type: "POST",
                    url: "{{url('/')}}/deltags"
            })
        }

        function addToTagList(name){
            names = [];
            value = "tag-" + name;
            $("#genre-tags").tokenfield("createToken",name);
            names = $("#genre-tags").tokenfield('getTokensList',", ").split(", ");
            return names;
        }

        function updateUserTags(){
            names = [];
            names = $("#genre-tags").tokenfield('getTokensList',", ").split(", ");
            return names;
        }

        function deleteTag(name){
            tagid = findTagId(name);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{url('/')}}/deltag",
                data: {
                    tagid: tagid
                },
                success: function (response) {
                    
                }
            });
        }

        function getUserTags(){
            tags = [];
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/getusertags",
                success: function (response) {

                    // iterate through users saved tags and add them
                    $.each(response,function(i,val){
                        tag = findTagName(val.itemID);
                        tags.push(tag);
                        addToTagList(tag);
                    });
                }
            });
            return tags;
        }
        // --- close button = a.close
        $(document).ready(function(){
            $("#genre-tags").tokenfield(
            )
            .on('tokenfield:createdtoken', function (e) {
                tagel = $("#genre-tags").data("bs.tokenfield").$input.parent().children().children('a:last');
                $(tagel).click(function(){
                    name = $(this).parent().children('span').text();
                    deleteTag(name);
                    $(this).parent().remove();
                });
            });
            genres = [];
            $.ajax({
                type: "GET",
                url: "{{url('/')}}/categories",
                success: function (response) {

                    allGenre = response;

                    $.each(response,function(i,val){
                        el = `<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 "><div class="genre-list-item" data-genreid="` + val.id + `">` + val.name + `</div></div>`;
                        $(".genre-list").append(el);
                    });
                    $(".genre-list-item").click(function(e){
                        addToTagList($(this).text());
                        saveTag(findTagId($(this).text()));
                    });

                    names = getUserTags();
                    $.each(names, function(i,val){
                        addToTagList(val.itemID);
                    });
                }
            });
        });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><h3 id="profile-heading" class="panel-heading">Profile</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="profile" >
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-xl-6 col-lg-6 col-12 p-5 img-container">
                    <img class="img-fluid" src="{{Auth::user()->avatar}}">
                        <div class="overlay"></div>
                        <div class="update">
                            <a href="#">
                            @isset($fileName)
                                {{$fileName}}
                            @endisset
                                <img src="{{url('/')}}/images/upload-image.png" alt="upload image" title="upload image" class="image-upload" data-toggle="modal" data-target="#upload-dialog">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-12 p-5">
                        <form action="{{url('/')}}/update-profile" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="name" class="form-control p_input" value="{{Auth::user()->name}}">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="email" class="form-control p_input" value="{{Auth::user()->email}}">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block enter-btn">Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <em>Prefered tags</em>
                            <input readonly type="text" class="form-control" id="genre-tags" value="" >
                        </div>
                        <em>Select your prefered tags:</em>
                        <div class="row genre-list"></div>
                    </div>
                   
                </div>
            </div>

            <div class="modal fade" role="dialog" id="upload-dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Upload profile image</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="form-group">
                                    <label>Image file</label>
                                    <input type="file" name="file" class="form-control" style="padding:1px;">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

@endsection
