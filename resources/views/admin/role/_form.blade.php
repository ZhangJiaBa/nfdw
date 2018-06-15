<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色标识名</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name" id="tag" value="{{ $name }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色展示名</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="display_name" id="tag" value="{{ $display_name }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色概述</label>
    <div class="col-md-5">
        <textarea name="description" class="form-control" rows="3">{{ $description }}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">权限选择</label>
</div>
<div class="form-group">
    <div class="form-group">
         <div class="form-group">
            <label class="control-label col-md-3 all-check">
                {{--{{$v['display_name']}}：--}}
            </label>
            <div class="col-md-6">
                {{--@if($perms[$v['id']] )--}}
                    @foreach($perms as $vv)
                        <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
                            <span class="checkbox-custom checkbox-default">
                            <i class="fa"></i>
                                <input class="form-actions"
                                       @if(in_array($vv['id'], $role_perms))
                                       checked
                                       @endif
                                       id="inputCheckbox{{$vv['id']}}" type="Checkbox" value="{{$vv['id']}}"
                                       name="permissions[]"> <label for="inputCheckbox{{$vv['id']}}">
                                    {{$vv['display_name']}}
                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                        </div>
                    @endforeach
                {{--@endif--}}
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.all-check').on('click', function () {

        });
    });
</script>

