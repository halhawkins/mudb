@extends('email')
@section('mainbody')
<h2>Thanks for signing up.</H2>
<p>Welcome, {{$data['userName']}}. Now you will be able to browse and listen to thousands of your favorite musical artists and receive taylor-made recommendations.</p>
<p>Click on this link or copy and paste into a browser to verify your account.</p>
<a style="word-wrap: break-word;" href="{{url('/')}}/verify/{{$data['userID']}}/{{$data['hashvalue']}}">{{url('/')}}/verify/{{$data['userID']}}/{{$data['hashvalue']}}</a>
<p style="text-align:center;"><a class="link-button" href="{{url('/')}}/verify/{{$data['userID']}}/{{$data['hashvalue']}}">Verify Email</a></p>
<p>After verifying, you will be able to opt-in to notifications about new releases and news about your favorite musical artists.</p>
<p>Unless you opt-in to notifications, the only emails you will recieve from this service will be to notify you in case of changes made to your account, or to notify you if changes are made to our terms of service, or user policies. </p>
<p>Please note that we will never share any of your personally identifiable information without your knowledge. This includes, but is not limited to, your name, email address, mailing address or credit card information. 
<p>&nbsp;</p>
@endsection