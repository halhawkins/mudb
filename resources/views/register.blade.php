@extends('layout')

@section('script')
@endsection
@section('mainbody')
<form action="register" method="post" style="width:100%">
    @csrf
<div class="col-12">
    <div class="row">
        <div class="col-12 toggle-bar"><h3 id="view-name" class="panel-heading">Register</h3></div>
    </div>
    <div class="row aux-bg1">
        <div class="col-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Name</span>
                </div>
                <input type="text" name="name" class="form-control" placeholder="Name" aria-label="name" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Email</span>
                </div>
                <input type="text" name="email" class="form-control" placeholder="Email address" aria-label="email" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Password</span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Password" aria-label="password" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Confirm</span>
                </div>
                <input type="password" name="confirm" class="form-control" placeholder="Confirm" aria-label="confirm" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <!-- <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Confirm</span>
                </div> -->
                <button class="form-control btn btn-light" name="submit">Submit</button>
            </div>
        </div>
    </div>

</div>
</form>
@endsection