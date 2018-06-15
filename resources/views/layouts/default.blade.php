<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap-table/dist/bootstrap-table.min.css")}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/plugin/font-awesome-4.5.0/css/font-awesome.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset("/plugin/ionicons-2.0.1/css/ionicons.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/skins/skin-blue.min.css")}}">
    <link rel="stylesheet" href="{{ asset("/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css")}}">
    {{--<link rel="stylesheet" href="{{ asset("/asset/css/test.css")}}">--}}

    {{--<link rel="stylesheet" href="{{ asset("/plugin/ludo-jquery-treetable/css/jquery.treetable.css")}}">--}}
    {{--<link rel="stylesheet" href="{{ asset("/asset/ludo-jquery-treetable/css/jquery.treetable.theme.default.css")}}">--}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <!--<script src="{{ asset("/plugin/html5shiv/3.7.3/html5shiv.min.js") }}"></script>-->
    <!--<script src="{{ asset("/plugin/respond/1.4.2/respond.min.js") }}"></script>-->
    </head>
    <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<!-- Header -->
@include('base.partials.header')

<!-- Sidebar -->
@include('base.partials.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        {{--@yield('breadcrumbs')--}}

        <h1>
            @yield('pageHeader')
            <small>{{ $page_description or null }}</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @yield('content')

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
@include('base.partials.footer')

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset("/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js")}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("/bower_components/AdminLTE/dist/js/app.min.js")}}"></script>
<!-- bootstrap-table -->
<script src="{{ asset("/bower_components/bootstrap-table/dist/bootstrap-table.min.js")}}"></script>
<script src="{{ asset("/bower_components/bootstrap-table/src/locale/bootstrap-table-zh-CN.js")}}"></script>
<script src="{{ asset("/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js")}}"></script>

@yield('javascript')

</body>
</html>
