@extends('layout')
@section('script')
<script>
$(document).ready(function(){
    setTimeout( function(){
        window.location.href = "{{url('/')}}";
    },5000);
});
</script>
@endsection
@section('mainbody')
<div>
<h3>Email address verified.</h3>
</div>
@endsection