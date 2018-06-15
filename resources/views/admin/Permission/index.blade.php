@extends('layouts.default')

@section('title','权限列表')

@section('pageHeader','权限管理')

{{--@section('breadcrumbs')--}}
    {{--<h5>--}}
        {{--{!! Breadcrumbs::render(Route::currentRouteName()) !!}--}}
    {{--</h5>--}}
{{--@endsection--}}

@section('content')

            <div class="row page-title-row" id="dangqian" style="margin:5px;">
                <div class="col-md-6">

                </div>

                @permission('admin.permission.create')
                <div class="col-md-6 text-right">
                    <a href="/admin/permission/create" class="btn btn-success btn-md"><i class="fa fa-plus-circle"></i> 添加权限 </a>
                </div>
                @endpermission
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
                        <div class="box-body">
                            <table id="permission-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="hidden-sm">权限id</th>
                                        <th class="hidden-sm">权限规则</th>
                                        <th class="hidden-sm">权限名称</th>
                                        <th class="hidden-md">权限创建日期</th>
                                        <th class="hidden-md">权限修改日期</th>
                                        <th data-sortable="false">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $permissions as $permission )
                                        <tr>
                                            <td>{{$permission->id}}</td>
                                            <td>{{$permission->name}}</td>
                                            <td>{{$permission->display_name}}</td>
                                            <td>{{$permission->created_at}}</td>
                                            <td>{{$permission->updated_at}}</td>
                                            <td>
                                                @permission('admin.permission.edit')
                                                <a href='{{ url("admin/permission/$permission->id/edit") }}'>
                                                    <i class="fa fa-fw fa-edit" title="修改"></i></a>
                                                @endpermission
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog modal-warning">
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
                        确认要删除这个软件吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="/admin/list">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> 确认
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop