@extends('layouts.default')

@section('title','角色列表')

@section('pageHeader','角色管理')

{{--@section('breadcrumbs')--}}
    {{--<h5>--}}
        {{--{!! Breadcrumbs::render(Route::currentRouteName()) !!}--}}
    {{--</h5>--}}
{{--@endsection--}}

@section('content')
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
            @permission('admin.role.create')
            <a href="/admin/role/create" class="btn btn-success btn-md">
                <i class="fa fa-plus-circle"></i> 添加角色
            </a>
            @endpermission
        </div>
    </div>

    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
            @include('admin.partials.errors')
            @include('admin.partials.success')
            <div class="box-body">
            <table id="tags-table" class="table table-bordered table-hover">
                {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th class="hidden-sm">角色名称</th>--}}
                        {{--<th class="hidden-sm">角色标签</th>--}}
                        {{--<th class="hidden-sm">角色概述</th>--}}
                        {{--<th class="hidden-md">角色创建日期</th>--}}
                        {{--<th class="hidden-md">角色修改日期</th>--}}
                        {{--<th data-sortable="false">操作</th>--}}
                    {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                    {{--@foreach ( $roles as $role )--}}
                        {{--<tr>--}}
                            {{--<td>{{$role->name}}</td>--}}
                            {{--<td>{{$role->display_name}}</td>--}}
                            {{--<td>{{$role->discription}}</td>--}}
                            {{--<td>{{$role->created_at}}</td>--}}
                            {{--<td>{{$role->updated_at}}</td>--}}
                            {{--<td>--}}
                                {{--@permission('admin.role.edit')--}}
                                {{--<a href='{{ url("admin/role/$role->id/edit") }}'>--}}
                                    {{--<i class="fa fa-fw fa-edit" title="修改"></i></a>--}}
                                {{--@endpermission--}}
                                {{--@permission('admin.role.destroy')--}}
                                {{--<a href='#' data-toggle="modal" data-target="#modal-delete">--}}
                                    {{--<i class="fa fa-fw fa-minus-circle text-danger" title="删除"></i></a>--}}
                                {{--@endpermission--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                {{--</tbody>--}}
            </table>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">提示</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        确认要删除这个角色吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            确认
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $(function () {
            var table = $("#tags-table").bootstrapTable({
                serverSide: true,
                url: "role?ajax=1",
                pagination: true,
                pageList: [5, 10, 15, 20],
                showColumns: true,
                search: true,
                columns: [
                    {
                        field: "name",
                        title: "角色名称"
                    },
                    {
                        field: "display_name",
                        title: "角色标签"
                    },
                    {
                        field: "discription",
                        title: "角色概述"
                    },
                    {
                        field: "created_at",
                        title: "角色创建日期"
                    },
                    {
                        field: "updated_at",
                        title: "角色修改日期"
                    },
                    {
                        field: 'operate',
                        title: '操作',
                        align: 'center',
                        events: operateEvents,
                        formatter: operateFormatter
                    }
                ],
            });
        });

        function operateFormatter(value, row, index) {
            return [
                '<a class="editRole" href="role/' + row.id + '/edit" title="编辑">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a>  ',
                '<a class="removeRole" href="javascript:void(0)" title="删除">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');
        }

        window.operateEvents = {
            'click .removeRole': function (e, value, row, index) {
                $('.deleteForm').attr('action', '/admin/role/' + row.id);
                $('#modal-delete').modal();
            },
        };
    </script>
@stop