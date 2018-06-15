@if (isset($data['project_name']))
    <tr>
        <td style="width: 350px;"><b>项目名称</b></td>
        <td >{{$data['project_name']}}</td>
    </tr>
@endif

@if (isset($data['rp_number']))
    <tr>
        <td style="width: 350px;"><b>报告编号</b></td>
        <td >{{$data['place']}}</td>
    </tr>
@endif

@if (isset($data['rp_type']))
    <tr>
        <td style="width: 350px;"><b>报告项目</b></td>
        <td >{{$data['rp_type']}}</td>
    </tr>
@endif
@if (isset($data['rp_format']))
    <tr>
        <td style="width: 350px;"><b>报告项目格式</b></td>
        <td >{{$data['rp_format']}}</td>
    </tr>
@endif
@if (isset($data['author']))
    <tr>
        <td style="width: 350px;"><b>编制人员</b></td>
        <td >{{$data['author'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['checker']))
    <tr>
        <td style="width: 350px;"><b>检测人员</b></td>
        <td >{{$data['checker'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['examine']))
    <tr>
        <td style="width: 350px;"><b>审核人员人员</b></td>
        <td >{{$data['examine'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['leader']))
    <tr>
        <td style="width: 350px;"><b>审批人员</b></td>
        <td >{{$data['leader'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['report_name']))
    <tr>
        <td style="width: 350px;"><b>报告附件</b></td>
        <td ><a href="{{$data['report_name']}}" class="btn btn-default">下载附件</a></td>
    </tr>
@endif



