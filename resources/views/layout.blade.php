<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/css/styles.css')}}">

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
@yield('script')
<body class="main-bg">
    <!--
        =====================================
        Start of Nav
        =====================================
    -->
    <nav class="navbar navbar-expand-md navbar-dark mb-3 primary-bg">
        <div class="container-fluid">
            <a href="#" class="navbar-brand mr-3" style="font-size:1.4em;"><span style="color:orange;">crA</span>pp</span> <span style="color:orange;">N</span>ame</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <!-- <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Services</a>
                    <a href="#" class="nav-item nav-link">About</a>
                    <a href="#" class="nav-item nav-link">Contact</a> -->
                </div>
                <div class="navbar-nav ml-auto">
      <form action="{{url('/')}}/search/" method="GET" class="form">

        <input type="search" placeholder="Search" class="search-field"  name="query"/>
        <button type="submit" class="search-button">
          <img src="{{asset('/images/magnifying-glass-icon-20.png')}}">
        </button>
      </form>
        @if(session()->has('username'))
            <img src="{{session('avatar')}}" style="width:32px;height:auto;border-radius:50%;"><span>Hello {{session('username')}}</span>
        @else
                    <!-- <input type="text" class="input" placeholder="search"><button type="submit"><i class="fa fa-search"></i></button> -->
                    <a href="#" class="nav-item nav-link" data-toggle="modal" data-target="#register-dialog">Register</a>
                    <a href="#" class="nav-item nav-link" data-toggle="modal" data-target="#login-modal">Login</a>
        @endif
                </div>
            </div>
        </div>    
    </nav>
    <!--
        =====================================
        End of Nav
        =====================================
    -->
    <!--
        =====================================
        Start of Container
        =====================================
    -->
    <div class="container">
        <div class="row">
 @yield('mainbody')
        </div>

        <hr>
    </div>
        <footer class="footer aux-bg2">
            <div class="row">
                <div class="col-md-6">
                    <p>Copyright &copy; 2021 Musicor</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="#">Terms of Use</a> 
                    <span class="text-muted mx-2">|</span> 
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
        </footer>

    <!--
        =====================================
        End of Container
        =====================================
    -->
    <div id="register-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Register</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                <form method="GET" action="{{url('/')}}/auth/redirect">
                    @csrf
                    <!-- <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="name" class="form-control p_input">
                    </div>
                    <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control p_input">
                    </div>
                    <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control p_input">
                    </div> -->

                    <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Register</button>
                    </div>

                    <p class="sign-up text-center">Already have an Account?<a href=""> Sign In</a></p>
                    <p class="terms">By creating an account you are accepting our<a href="#"> Terms & Conditions</a></p>
                </form>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="register-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Register</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div style="text-align:center;margin:4px;"><a class="btn btn-dark" href="{{url('/')}}/auth/redirect"><img src="{{url('/')}}/images/google-logo.png" style="width:32px;height:32px;margin-right:8px;">Register using Google</a></div>
                    <div style="text-align:center;margin:4px;"><a class="btn btn-dark" href="{{url('/')}}/register"><img src="{{url('/')}}/images/register-user.png" style="width:32px;height:32px;margin-right:8px;">Create and account using email address.</a></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="login-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">


                <form method="post" action="">
                    @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="name" class="form-control p_input">
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control p_input">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
                </div>

                <p class="sign-up">Don't have an Account?<a href=""> Sign Up</a></p>
                </form>
                                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>