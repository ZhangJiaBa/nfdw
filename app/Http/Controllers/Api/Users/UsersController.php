<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\Admin\Administrator;
use App\Models\Department\Department;
use App\Models\Workflow\WorkflowCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function getUserInfo(Request $request)
    {
        $params = $request->only(['username', 'password']);

        //验证参数
        $messages = [
            'username.required' => '姓名不能为空！',
            'password.required' => '密码不能为空！',
        ];
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required',
        ],$messages);

        if ($validator->fails()) {
            //返回第一条错误
            $error = $validator->errors()->first();
            return $error;
        }

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($params)) {
                return response()->json(['error' => '账号或密码错误','status'=>'500'], 200);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token','code'=>'500'], 500);
        }

        // all good so return the token

        if (Auth::attempt($params)) {
            $user = DB::table('users')->where('username',$params['username'])->select('id','username','name','dp_id','sign_img','avatar','wf_usr_id')->first();
            $user->avatar = url($user->avatar);
            $user->sign_img = url($user->sign_img);
            $user->token = "Bearer ".$token;
            $user->dp_id = Department::where('id',$user->dp_id)->value('name');

            return response()->json($user);
        }else{
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    public function updateUserInfo(Request $request){
        $params = $request->only('user_id','name','password','old_password');
        try{
            if (!empty($params['name'])){
                $rel = DB::table('users')->where('id',$params['user_id'])->update(['name'=>$params['name']]);
            }
            if (!empty($params['password'])){
                $username= Administrator::where('id',$params['user_id'])->value('username');
                $data['username'] = $username;
                $data['password'] = $params['old_password'];
                $auth = Auth::attempt($data);
                if ($auth){
                    $password = bcrypt($params['password']);
                    $rel = DB::table('users')->where('id',$params['user_id'])->update(['password'=>$password]);
                }else{
                    return response()->json(['error' => '原密码不正确'], 500);
                }

            }
            if ($rel){
                $user = DB::table('users')->where('id',$params['user_id'])->select('id','username','name','dp_id','sign_img','avatar','wf_usr_id')->first();
                $user->avatar = url($user->avatar);
                $user->sign_img = url($user->sign_img);
                $user->dp_id = Department::where('id',$user->dp_id)->value('name');
                return response()->json($user);
            }else{
                return response()->json(['error' => '更新失败'], 500);
            }
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }


    }
}
