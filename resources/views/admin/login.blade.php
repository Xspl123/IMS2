<!DOCTYPE html>
<html>
<head>
    <title>login panel</title>
    <link href="css/style.css" rel='stylesheet' type='text/css'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="images/logo-color.jpg" rel="icon">
    <meta name="keywords"
          content="Simple Login Form,Login Forms,Sign up Forms,Registration Forms,News latter Forms,Elements" ./>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700'
          rel='stylesheet' type='text/css'>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> 
    

</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100">
        <div class="position-relative z-index-5">
            <div class="row">
                <div class="col-xl-6 col-xxl-7 col-12 col-sm-6 col-lg-6 authentication-login-left">
                    <a href="./index.html" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                        <img src="images/logo-color_2.png" width="180" alt="">
                    </a> -

                      <img src="{{asset('images/5858333.webp')}}" alt="" class="img-fluid" width="800">
                    
                </div> 
                <div class="col-xl-6 col-xxl-5 col-12 col-sm-6 col-lg-6" >
                    <div
                        class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                        <div class="col-sm-8 col-md-6 col-xl-9">
                            <h2 class="mb-3 fs-7 fw-bolder" style="text-align: center">Welcome To Vert-Age</h2>
                            <p class="fs-7 fw-bolder" style="text-align: center">Inventory Management system</p>
                            <div class="login-page">
                          
                                <div class="">
                                    @if(Session::has('message-error'))
                                        <div class="alert alert-danger">
                                            <strong>Danger!</strong> {{ Session::get('message-error') }}
                                        </div>
                                        @elseif(Session::has('message-success'))
                                        <div class="alert alert-success">
                                            <strong>Success!</strong> {{ Session::get('message-success') }}
                                        </div>
                                    @endif
                              <form method="POST" action="{{ route('login/process') }}" class="login-form">
                                {{ csrf_field() }}
                                <div class="mb-3">
                                    
                                    <label for="exampleInputEmail1" class="form-label">Email</label>
                                    <div class="form-group">
                                    <input type="email"  id="email" type="email" name="email" class="form-control"
                                        aria-describedby="emailHelp" required placeholder="Enter valid email address">
                                        <span class="example_input"><i class="fad fa-user"></i></span>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <div class="form-group">
                                    <input id="password" type="password" name="password" class="form-control" name="password" value="" required placeholder="Enter valid password">
                                    <span toggle="#password-field"
                                        class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <span class="example_input"><i class="fas fa-key"></i></span>
                                    </div>
                                </div>
                                {{-- <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input primary" type="checkbox" value=""
                                            id="flexCheckChecked" checked="">
                                        <label class="form-check-label text-dark" for="flexCheckChecked">
                                            Remeber this Device
                                        </label>
                                    </div>
                                    <a class="text-primary fw-medium" href="forgetpassword.html">Forgot Password ?</a>
                                </div> --}}
                               <button
                                    class="btn btn-primary btn_login w-100 py-8 mb-4 rounded-2"><i class="fad fa-sign-in-alt"></i> Sign In</button> 
                               {{-- <div class="d-flex align-items-center justify-content-center">
                                    <p class="fs-4 mb-0 fw-medium">Already hava an account? </p>
                                    <a class="text-primary fw-medium ms-2"
                                        href="signup.html"> Login</a>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 welcome-footer text-center animated fadeInUp" style="animation-delay:0.8s;">
            <a href="http://xenottabyte.in/" target="_blank"><h1 class=" text-footer-1">Xenottabyte Services Pvt. Ltd</h1></a>
    
            </div>
    </div>
</div> 
    
</body>
</html>