<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kopi Kita</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/dist/css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <!-- <a href="#"><b>Kopi</b>Kita</a> -->
            <img src="{{asset('img/logo.png')}}" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ubah Password</p>

                    @include('layouts.alert')


                <form action="{{url('reset')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru">
                        @error('password') <div class="invalid-feedback">{{$message}}</div> @enderror
                        <!-- <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div> -->
                    </div>
                    <div class="input-group mb-3">
                        <input type="hidden" name="id" class="form-control" value="{{$id}}">
                        <input type="password" name="ulangipassword" class="form-control @error('ulangipassword') is-invalid @enderror" placeholder="Ulangi Password">
                        @error('ulangipassword') <div class="invalid-feedback">{{$message}}</div> @enderror

                        <!-- <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Ubah</button>

                        </div>
                        <div class="col-4">
                        <a href="{{url('')}}">
                            <button type="button" class="btn btn-secondary btn-block btn-flat">Kembali</button>
                        </a>

                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{asset('/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

</body>

</html>