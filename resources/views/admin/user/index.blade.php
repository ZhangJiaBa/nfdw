@extends('layouts.default')

@section('title','用户列表')

@section('pageHeader','用户管理')

{{--@section('breadcrumbs')--}}
    {{--<h5>--}}
        {{--{!! Breadcrumbs::render(Route::currentRouteName()) !!}--}}
    {{--</h5>--}}
{{--@endsection--}}

@section('content')

    @include('admin.partials.errors')
    @include('admin.partials.success')

    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6"></div>
        <div class="col-md-6 text-right">
            @permission('admin.user.create')
            <a href='#' data-toggle="modal" data-target="#modal-upload" class="btn btn-success btn-md">
                <i class="fa fa-fw fa-plus-circle" title=""></i> 导入联系人
            </a>
            <a href="/admin/user/create" class="btn btn-success btn-md">
                <i class="fa fa-plus-circle"></i> 添加用户
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
                 <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">

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
                        确认要删除这个用户吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class=".modal-del-btn btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            确认
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-upload" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data">

                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal">
                            ×
                        </button>
                        <h4 class="modal-title">上传</h4>
                    </div>

                    <div class="modal-body">
                        <p class="lead">
                        <div class="form-group">
                            <label class="control-label" for="inputfile">选择文件</label>
                            <input type="file" name="file" id="inputfile">
                        </div>

                        <div class="form-group">
                            <label class="control-label"  for="dropdownMenu1">导入类型</label>
                            <select name="type">
                                <option value="1">外部联系人</option>
                                <option value="2">企业成员</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label"  for="dropdownMenu1">导入企业成员请选择角色</label>
                            <select name="roles">
                                @foreach($roles as $r)
                                    <option value="{{$r->id}}">{{$r->display_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="modal-del-btn btn btn-success">
                            确认
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('javascript')

    <script>

        $(function () {
            var table = $("#tags-table").bootstrapTable({
                serverSide: true,
                url: "user?ajax=1",
                pagination: true,
                pageList: [5, 10, 15, 20],
                showColumns: true,
                search: true,
                columns: [
                    {
                        field: "qy_id",
                        title: "账号"
                    },
                    {
                        field: "name",
                        title: "名字"
                    },
                    {
                        field: "mobile",
                        title: "手机"
                    },
                    {
                        field: "email",
                        title: "邮箱"
                    },
                    {
                        field: "weixinid",
                        title: "微信"
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
                '<a class="editUser" href="user/' + row.id + '/edit" title="编辑">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a>  ',
                '<a class="removeUser" href="javascript:void(0)" title="删除">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');
        }

        window.operateEvents = {
            'click .removeUser': function (e, value, row, index) {
                $('.deleteForm').attr('action', '/admin/user/' + row.id);
                $('#modal-delete').modal();
            },
        };

    </script>

@stop