@extends('email')
@section('mainbody')
<h2>Password reset</H2>
<p>You are recieving this message because you requested a password reset from Violine. If you did not make this request, you may safely ignore it.</p>
<p>To select a new password, please click the following link.</p>
<a style="word-wrap: break-word;" href="{{url('/')}}/passwordchange/{{$hashvalue}}">{{url('/')}}/passwordchange/{{$hashvalue}}</a>
<p style="text-align:center;"><a class="link-button" href="{{url('/')}}/passwordchange/{{$hashvalue}}">Change password</a></p>
<p></p>
<p>Tis link will expire after one day.</p>
<p>Please note that we will never share any of your personally identifiable information without your knowledge. This includes, but is not limited to, your name, email address, mailing address or credit card information. 
<p>&nbsp;</p>
@endsection