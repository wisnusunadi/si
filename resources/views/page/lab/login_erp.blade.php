<html>
    <head>
        <title>ERP</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/image/logo/logo_title.ico') }}" />
        <style>
            .login-page {
                height: 100vh;
                min-height: 500px;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/adminlte.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.6.2.0.all.min.css') }}" />
        <!-- <link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/adminlte.min.css') }}" /> -->
        <!-- <link rel="stylesheet" href="{{ asset('assets/fontawesome-free/css/all.min.css') }}" /> -->
        <!-- <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}"> -->
    </head>
    <body>
        <div class="login-page">
            <div class="container">
                <div class="card flex-row p-5">
                    <div class="col-6 d-flex justify-content-center">
                        <div class="pe-5" style="border-right: 1px solid rgba(0, 0, 0, 0.125)">
                            <img src="{{ asset('assets/image/logo/login.png') }}" alt="logo" style="max-width: 90%" />
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <div class="ps-5">
                            <div class="d-flex justify-content-center">
                                <img src="{{ asset('assets/image/logo/sinko.png') }}" alt="sinko" style="width: 30%" />
                            </div>
                            <div>
                                <div>

                                @if($errors->any())
                                <p>{{$errors->first()}}</p>
                                @endif

                                    <form action="/login_post" method="post">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span><i class="fas fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="password" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span><i class="fas fa-lock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <button type="submit" class="btn btn-block btn-primary login_button">
                                                <span class="fas fa-sign-in-alt"></span>
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>

    </html>
    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <!-- <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script> -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/font-awesome.6.2.0.all.min.js') }}"></script>
    <script>
    </script>
