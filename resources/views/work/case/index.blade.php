@extends('base.index')

@section('content')
    <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{ asset("/bower_components/bootstrap-table/dist/bootstrap-table.min.css")}}">
    <section class="content-header">

        {{--@yield('breadcrumbs')--}}

        <h1>
            工作流审批
            <small>列表</small>
        </h1>
    </section>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">

        </div>
    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover" data-toolbar="#toolbar">

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var table = $("#tags-table").bootstrapTable({
                serverSide: true,
                url: "{{url('work/case?ajax=1')}}",
                pagination: true,
                pageList: [5, 10, 15, 20],
                showColumns: true,
                search: true,
                showRefresh: true,
                columns: [
                    {
                        field: "app_number",
                        title: "ID"
                    },
                    {
                        field: "app_pro_title",
                        title: "流程类型"
                    },
                    {
                        field: "usrcr_usr_username",
                        title: "我的完成情况",
                        formatter: delStatusFormatter
                    },
                    {
                        field: "appdelcr_app_tas_title",
                        title: "当前进行至"
                    },
                    {
                        field: "usrcr_usr_username",
                        title: "当前处理人"
                    },
                    {
                        field: "app_status",
                        title: "流程状态",
                        formatter: appStatusFormatter
                    },
                    {
                        field: 'operate',
                        title: '操作',
                        align: 'center',
                        formatter: operateFormatter
                    }
                ],
            });
        });

        function operateFormatter(value, row, index) {
            console.log(row);
            return [
                '<a class="viewMeeting" href="{{url('work/case')}}/' + row.app_uid + '" title="编辑">',
                '<i class="glyphicon glyphicon-eye-open"></i>',
                '</a>  '
            ].join('');
        }

        function delStatusFormatter(value, row, index) {
            if (value != "{{\Encore\Admin\Facades\Admin::user()->username}}" || row.app_status=="COMPLETED") {
                return '<i class="fa fa-check text-success -align-right">完成</i>';
            } else if (value == "{{\Encore\Admin\Facades\Admin::user()->username}}") {
                return '<i class="fa fa-hourglass-2">待办</i>';
            }
        }

        function appStatusFormatter(value, row, index) {
            if (value == 'COMPLETED') {
                return '<i class="fa fa-check text-success -align-right">结束</i>';
            } else if (value == 'TO_DO') {
                return '<i class="fa fa-hourglass-2">进行中</i>';
            }
        }


    </script>

@stop
