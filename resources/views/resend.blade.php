@extends('layout')
@section('script')
<script>
$(document).ready(function(){
    $(".resend").click(function(){
        $.ajax({
            method: 'GET',
            url: "{{url('/')}}/public/sendverification",
            success: function(){
                window.location.href = "{{url('/')}}/public/";
            }
        });
    });
});
</script>
@endsection

@section('mainbody')
<div>
<h3>Verification token has expired. <button class="btn btn-warning resend">Resend?</button></h3>
</div>
@endsection