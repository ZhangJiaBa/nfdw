@extends('layouts.default')

@section('title','菜单列表')

@section('pageHeader','菜单管理')

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
            @permission('admin.menu.create')
            <a href="{{ route('admin.menu.create') }}" class="btn btn-success btn-md">
                <i class="fa fa-plus-circle"></i> 添加菜单
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
         <div class="col-xs-12">
             <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                 <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th class="hidden-sm">ID</th>--}}
                                {{--<th class="hidden-sm">父菜单ID</th>--}}
                                {{--<th class="hidden-md">权限</th>--}}
                                {{--<th class="hidden-md">路由</th>--}}
                                {{--<th class="hidden-md">菜单名称</th>--}}
                                {{--<th data-sortable="false">操作</th>--}}
                            {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach ( $menus as $menu )--}}
                            {{--<tr>--}}
                                {{--<td>{{$menu->id}}</td>--}}
                                {{--<td>{{$menu->parent_id}}</td>--}}
                                {{--<td>{{$menu->permission}}</td>--}}
                                {{--<td>{{$menu->route}}</td>--}}
                                {{--<td>{{$menu->display_name}}</td>--}}
                                {{--<td>--}}
                                    {{--@permission('admin.menu.edit')--}}
                                    {{--<a href='{{ route('admin.menu.edit', ['menu' => $menu->id]) }}'>--}}
                                        {{--<i class="fa fa-fw fa-edit" title="修改"></i></a>--}}
                                    {{--@endpermission--}}
                                    {{--@permission('admin.menu.destroy')--}}
                                    {{--<a href='#' attr="{{$menu->id}}" data-toggle="modal" data-target="#modal-delete">--}}
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
                        确认要删除这个菜单吗?
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
                url: "menu?ajax=1",
                pagination: true,
                pageList: [5, 10, 15, 20],
                columns: [
                    {
                        field: "id",
                        title: "ID"
                    },
                    {
                        field: "parent_id",
                        title: "父菜单ID"
                    },
                    {
                        field: "permission",
                        title: "权限"
                    },
                    {
                        field: "route",
                        title: "路由"
                    },
                    {
                        field: "display_name",
                        title: "菜单名称"
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
                '<a class="editMenu" href="menu/' + row.id + '/edit" title="编辑">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a>  ',
                '<a class="removeMenu" href="javascript:void(0)" title="删除">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');
        }

        window.operateEvents = {
            'click .removeUser': function (e, value, row, index) {
                $('.deleteForm').attr('action', '/admin/menu/' + row.id);
                $('#modal-delete').modal();
            },
        };

    </script>

@stop