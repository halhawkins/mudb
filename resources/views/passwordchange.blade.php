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
.passwordreq{
    margin-top:1em;
    margin-bottom:1em;
    width: 100%;
}
.response-div{
    margin-top:1em;
    margin-bottom:1em;
    width: 100%;
    padding: .5em;
}

    </style>
    <link href="{{url('/')}}/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
    <script src="{{url('/')}}/js/bootstrap-tokenfield.js"></script>
    <script>
    $(document).ready(function(){
        $(".enter-btn").click(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "{{url('/')}}/updatepassword",
                data: {
                    oldPassword: $("#oldpassword").val(),
                    newPassword: $("#newpassword").val(),
                    token: "{{$token}}",
                    confirm: $("#confirm").val()
                },
                success: function (response) {
                    $(".response-div").removeClass("bg-danger").addClass("bg-success text-white").text("Password successfully changed.");
                    setTimeout(() => {
                        window.location.href = "{{url('/')}}/";
                    }, 5000);
                },
                error: function(xhr,textStatus, errorThrown){
                    $(".response-div").removeClass('bg-success').addClass("bg-danger text-white").text(xhr.responseJSON.message);
                    console.log(xhr);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    });
        
    </script>
@endsection

@section('mainbody')
            <div class="col-md-12 toggle-bar"><h3 id="profile-heading" class="panel-heading">Change Password</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="profile" >
                    <div class="col-xl-12 col-lg-12 col-12 p-5">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="newpassword" id="newpassword" class="form-control p_input" value="">
                            </div>
                            <div class="form-group">
                                <label>Re-enter New Password</label>
                                <input type="password" name="confirm" id="confirm" class="form-control p_input" value="">
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block enter-btn">Submit</button>
                            </div>
                        <div class="text-center response-div">
                        </div>
                    </div>
                   
                </div>
            </div>
@endsection
