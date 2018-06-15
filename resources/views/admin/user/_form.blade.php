@if (!isset($id))
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">账号</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="qy_id" id="tag"
               placeholder="企业号唯一ID" value="{{ $qy_id or null }}" autofocus>
    </div>
</div>
@endif

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">姓名</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name" id="tag" value="{{ $userinfo->name or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <p style="color:#777" class="col-md-offset-3">
        身份验证信息
        <span>（以下3种信息不能同时为空）</span>
    </p>
    <label for="tag" class="col-md-3 control-label">手机</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="mobile" id="tag" value="{{ $userinfo->mobile or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">微信</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="weixinid" id="tag"
               placeholder="只允许字母、数字和下划线，并以字母开头" value="{{ $userinfo->weixinid or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">邮箱</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="email" id="tag" value="{{ $userinfo->email or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">职位</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="position" id="tag" value="{{ $userinfo->position or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">单位</label>
    <div class="col-md-5">
        <select class="form-control" name="workplace_id">
            @foreach ($department as $d)
                @if ($d->parent_id == 1)
                    <option class="col-md-5" value='{{$d->department_id}}'
                            @if($d->department_id == $userinfo->workplace_id)
                                selected="selected"
                            @endif>{{ $d->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">科室</label>
    <div class="col-md-5">
        <select class="form-control" name="department_id">
            @foreach ($department as $d)
                @if ($d->parent_id != 1 && $d->parent_id != 0)
                    <option class="col-md-5" value='{{$d->department_id}}'
                            @if($d->department_id == $userinfo->department_id)
                            selected="selected"
                            @endif>{{ $d->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>


<div class="form-group">
    <label for="tag" class="col-md-3 control-label">固话</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="fixed_phone" id="tag" value="{{ $userinfo->fixed_phone or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">QQ</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="qq" id="tag" value="{{ $userinfo->qq or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色列表</label>
        <div class="col-md-6">
            @foreach($rolesAll as $v)
                <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
                    <span class="checkbox-custom checkbox-default">
                        <i class="fa"></i>
                            <input class="form-actions"
                                   @if(in_array($v['id'],$roles)) checked @endif
                                   id="inputChekbox{{$v['id']}}" type="Checkbox" value="{{$v['id']}}" name="roles[]">
                            <label for="inputChekbox{{$v['id']}}"> {{$v['display_name']}} </label>
                    </span>
                </div>
            @endforeach
        </div>

</div>

