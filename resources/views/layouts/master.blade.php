<!DOCTYPE html>
<!--
    This is a starter template page. Use this page to start your new project from
    scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Y.O.U</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/DataTables/datatables.css')}}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

    {{-- <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css"> --}}

    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{asset('/plugins/fullcalendar/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/fullcalendar-daygrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/fullcalendar-timegrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/fullcalendar-bootstrap/main.min.css')}}">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<style>
    .error {
        color: red;
        font-size: 15px;
    }
</style>

<body class="hold-transition layout-top-nav" style="background-color:#f7ce05">
    <div class="wrapper">
        <div class="container-fluid">
            @include('layouts.navbar')
            @include('layouts.alert')
            @yield('content')
        </div>

        <!-- Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    {{-- <script src="{{asset('/plugins/datatables/jquery.dataTables.js')}}"></script> --}}
    <!-- jQuery -->
    <script src="{{asset('/plugins/jquery/jquery.min.js')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>

    <script src="{{asset('/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('/plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('/dist/js/adminlte.min.js')}}"></script>
    <script charset="utf8" src="{{asset('/DataTables/datatables.js')}}"></script>
    <script src="{{asset('/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>

    <script src="{{asset('/dist/js/demo.js')}}"></script>

    <!-- fullCalendar 2.2.5 -->
    <script src="{{asset('/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('/plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('/plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('/plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('/plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('/plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    
    @yield('footer')
</body>

</html>
