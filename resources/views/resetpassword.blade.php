@extends('layout')
@section('script')
<script>
$(document).ready(function(){
    $(".enter-btn").click(function(){
        $.ajax({
            method: 'GET',
            url: "{{url('/')}}/passwordreq/" + $("#email").val(),
            success: function(){
                $(".response-div").addClass('bg-success').text("Reset request sent. Please check your email.");
            },
            error: function(xhr,textStatus, errorThrown){
                $(".response-div").addClass('bg-danger').text(xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endsection

@section('mainbody')
<div class="col-md-12 toggle-bar"><h3 id="profile-heading" class="panel-heading">Forgot Password</h3><div class="toggle-panel"></div>
                <!-- #recent-releases filled in by ajax request handler -->
            </div>
            <div class="col-md-12">
                <div class="row aux-bg1" id="profile" >
                    <div class="col-xl-12 col-lg-12 col-12 p-5">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" id="email" class="form-control p_input" value="">
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