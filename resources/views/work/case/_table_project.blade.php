@if (isset($data['project_name']))
<tr>
    <td style="width: 350px;"><b>项目名称</b></td>
    <td >{{$data['project_name']}}</td>
</tr>
@endif
@if (isset($data['place']))
<tr>
    <td style="width: 350px;"><b>项目地址</b></td>
    <td >{{$data['place']}}</td>
</tr>
@endif

@if (isset($data['entrustment_unit']))
<tr>
    <td style="width: 350px;"><b>委托单位</b></td>
    <td >{{$data['entrustment_unit']}}</td>
</tr>
@endif
@if (isset($data['entrustment_number']))
    <tr>
        <td style="width: 350px;"><b>委托编号</b></td>
        <td >{{$data['entrustment_number']}}</td>
    </tr>
@endif
@if (isset($data['contract_number']))
    <tr>
        <td style="width: 350px;"><b>合同编号</b></td>
        <td >{{$data['contract_number']}}</td>
    </tr>
@endif
@if (isset($data['contacts']))
    <tr>
        <td style="width: 350px;"><b>委托方联系人</b></td>
        <td >{{$data['contacts']}}</td>
    </tr>
@endif
@if (isset($data['telephone']))
    <tr>
        <td style="width: 350px;"><b>委托方联系人电话</b></td>
        <td >{{$data['telephone']}}</td>
    </tr>
@endif
@if (isset($data['build_unit']))
    <tr>
        <td style="width: 350px;"><b>建设单位</b></td>
        <td >{{$data['build_unit']}}</td>
    </tr>
@endif

@if (isset($data['design_unit']))
<tr>
    <td style="width: 350px;"><b>设计单位</b></td>
    <td >{{$data['design_unit']}}</td>
</tr>
@endif

@if (isset($data['doing_unit']))
<tr>
    <td style="width: 350px;"><b>实施单位</b></td>
    <td >{{$data['doing_unit']}}</td>
</tr>
@endif
@if (isset($data['check_unit']))
    <tr>
        <td style="width: 350px;"><b>监理单位</b></td>
        <td >{{$data['check_unit']}}</td>
    </tr>
@endif
@if (isset($data['project_detail']))
    <tr>
        <td style="width: 350px;"><b>项目概况</b></td>
        <td >{{$data['project_detail']}}</td>
    </tr>
@endif
@if (isset($data['information_of_the_client']))
    <tr>
        <td style="width: 350px;"><b>委托方提供的资料</b></td>
        <td >{{$data['information_of_the_client']}}</td>
    </tr>
@endif
@if (isset($data['testing_requirements']))
    <tr>
        <td style="width: 350px;"><b>检验检测依据</b></td>
        <td >{{$data['testing_requirements']}}</td>
    </tr>
@endif
@if (isset($data['author']))
    <tr>
        <td style="width: 350px;"><b>项目编制</b></td>
        <td >{{$data['author'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['checker']))
    <tr>
        <td style="width: 350px;"><b>项目校验</b></td>
        <td >{{$data['checker'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['examine']))
    <tr>
        <td style="width: 350px;"><b>项目审核</b></td>
        <td >{{$data['examine'][0]->name}}</td>
    </tr>
@endif
@if (isset($data['leader']))
    <tr>
        <td style="width: 350px;"><b>项目批准</b></td>
        <td >{{$data['leader'][0]->name}}</td>
    </tr>
@endif



