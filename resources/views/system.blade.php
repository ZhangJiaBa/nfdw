@extends('base.index')

@section('content')
    <div class="box-header with-border">
        <h2 class="box-title">系统信息</h2>

        {{--<div class="box-tools pull-right">--}}
            {{--<a class="btn btn-default" href="{{url('system/edit')}}"><i class="fa fa-edit"></i>修改</a>--}}
        {{--</div>--}}
    </div>
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap-table/dist/bootstrap-table.min.css")}}">
    <div class="box box-default">
        <!-- /.box-header -->
        <div class="box-body" style="margin-top:20px;margin-left: 20px;padding: 10px;">
            <div class="table-responsive">
                <table class="table table-striped">
                    @foreach($envs as $env)
                        <tr>
                            <td width="200px">{{ $env['name'] }}</td>
                            <td>{{ $env['value'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
    </div>

@stop
