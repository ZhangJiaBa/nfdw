@if (isset($data['project_name']))
    <tr>
        <td style="width: 350px;"><b>项目名称</b></td>
        <td >{{$data['project_name']}}</td>
    </tr>
@endif

@if (isset($data['contract_num']))
    <tr>
        <td style="width: 350px;"><b>合同编号</b></td>
        <td >{{$data['contract_num']}}</td>
    </tr>
@endif

@if (isset($data['party_a']))
    <tr>
        <td style="width: 350px;"><b>委托单位</b></td>
        <td >{{$data['party_a']}}</td>
    </tr>
@endif
@if (isset($data['sign_date']))
    <tr>
        <td style="width: 350px;"><b>合同签订日期</b></td>
        <td >{{$data['sign_date']}}</td>
    </tr>
@endif
@if (isset($data['limit_time']))
    <tr>
        <td style="width: 350px;"><b>合同工期</b></td>
        <td >{{$data['limit_time']}}</td>
    </tr>
@endif
@if (isset($data['dp_id']))
    <tr>
        <td style="width: 350px;"><b>任务完成部门</b></td>
        <td >{{$data['dp_id']}}</td>
    </tr>
@endif
@if (isset($data['people']))
    <tr>
        <td style="width: 350px;"><b>项目负责人</b></td>
        <td >{{$data['people']}}</td>
    </tr>
@endif
@if (isset($data['entry_time']))
    <tr>
        <td style="width: 350px;"><b>要求进场时间</b></td>
        <td >{{$data['entry_time']}}</td>
    </tr>
@endif
@if (isset($data['finish_time']))
    <tr>
        <td style="width: 350px;"><b>预计完成时间</b></td>
        <td >{{$data['finish_time']}}</td>
    </tr>
@endif
@if (isset($data['author']))
    <tr>
        <td style="width: 350px;"><b>生产经营部新建</b></td>
        <td >{{$data['author'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['checker']))
    <tr>
        <td style="width: 350px;"><b>生产经营部合同核查</b></td>
        <td >{{$data['checker'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['for_instance']))
    <tr>
        <td style="width: 350px;"><b>检测部门经理详审</b></td>
        <td >{{$data['for_instance'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['manage_instance']))
    <tr>
        <td style="width: 350px;"><b>副总经理详审核</b></td>
        <td >{{$data['manage_instance'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['examine']))
    <tr>
        <td style="width: 350px;"><b>总经理审核</b></td>
        <td >{{$data['examine'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['leader']))
    <tr>
        <td style="width: 350px;"><b>董事长批准</b></td>
        <td >{{$data['leader'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['contract_files']))
    <tr>
        <td style="width: 350px;"><b>合同文件附件</b></td>
        <td ><a href="{{$data['contract_files']}}" class="btn btn-default">下载附件</a></td>
    </tr>
@endif



