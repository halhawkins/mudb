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

    </style>
    <script>

        $(document).ready(function(){
            // $(".image-upload").click(function(){
            //     alert("boing");
            // });
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
