
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">菜单名</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="display_name" id="tag" value="{{ $display_name or null }}" autofocus>
        {{--<input type="hidden" class="form-control" name="cid" id="tag" value="{{ $cid }}" autofocus>--}}
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">权限</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="permission" id="tag" value="{{ $permission or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">路由</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="route" id="tag" value="{{ $route or null }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">父级菜单</label>
</div>
<div class="form-group">
    <div class="form-group">
        <div class="form-group">
            <label class="control-label col-md-3 all-check">
                {{--{{$v['display_name']}}：--}}
            </label>
            <div class="col-md-6">
                <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
                            <span class="checkbox-custom checkbox-default">
                            <i class="fa"></i>
                                <input class="form-actions" id="inputRadio0" type="radio" value="0" name="parent_id">
                                <label for="inputRadio0">
                                    无
                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                </div>
                @foreach($menus as $m)
                    <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
                            <span class="checkbox-custom checkbox-default">
                            <i class="fa"></i>
                                <input class="form-actions" id="inputRadio{{$m->id}}" type="radio"
                                       value="{{$m->id}}" name="parent_id">
                                <label for="inputRadio{{$m->id}}">
                                    {{$m->display_name}}
                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

