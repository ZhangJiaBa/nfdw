@extends('base.index')

@section('content')
    <section class="content-header">

        {{--@yield('breadcrumbs')--}}

        <h1>
            查看审批
            <small>审批详情</small>
        </h1>
    </section>
    <div class="row">
        <div class="col-xs-10 col-md-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        <tbody>
                            @if ($type == 1)
                                @include('work.case._table_report')
                            @elseif($type == 2)
                                @include('work.case._table_contract')
                            @elseif($type == 3)
                                @include('work.case._table_project')
                            @endif
                        </tbody>
                    </table>

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">审批</h3>
                        </div>
                        @if(\Encore\Admin\Facades\Admin::user()->wf_usr_id == $usr_uid)
                        <div class="box-footer" id="examine">
                            @if ($type == 1 && $index == \App\Models\Workflow\WorkflowCase::BAOGAOSHENPI_LAST_INDEX)
                                <a class="btn btn-info" href="{{url('case_route?type='.$type.'&caseid='.$caseId.'&agree=1&index='.$index.'&task='.$task)}}" >同意</a>
                            @elseif($type == 2 && $index == \App\Models\Workflow\WorkflowCase::HETONGSHENPI_LAST_INDEX)
                                <a class="btn btn-info" href="{{url('case_route?type='.$type.'&caseid='.$caseId.'&agree=1&index='.$index.'&task='.$task)}}" >同意</a>
                            @elseif($type == 3 && $index == \App\Models\Workflow\WorkflowCase::XIANGMUSHENPI_LAST_INDEX)
                                <a class="btn btn-info" href="{{url('case_route?type='.$type.'&caseid='.$caseId.'&agree=1&index='.$index.'&task='.$task)}}" >同意</a>
                            @else
                                <a class="btn btn-info" href="{{url('create_route?type='.$type.'&caseid='.$caseId.'&agree=1&index='.$index.'&task='.$task)}}" >同意</a>
                            @endif
                            <a class="btn btn-danger" href="{{url('create_route?type='.$type.'&caseid='.$caseId.'&agree=0&index='.$index)}}">拒绝</a>
                        </div>
                        @else
                            <div class="box-footer">审批已提交</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop