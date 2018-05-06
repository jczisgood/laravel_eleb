<?php

namespace App\Http\Controllers;

use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //
    public function change(Request $request)
    {
        //判断传过来的旧密码是否正确
//        dd($request->oldPassword);
//        dd(bcrypt(11));die;
        if (Hash::check($request->oldPassword,Auth::user()->password)){
            //如果正确,修改密码
            $newpassword=bcrypt($request->newPassword);
//            dd($newpassword);
           DB::update("update users set password='{$newpassword}' where id=?",[Auth::user()->id]);
            return [
                'status'=>'true',
                'message'=>'修改成功',
            ];

        }else{
            return ['status'=>'false','message'=>'修改失败,旧密码错误'];
        }
    }

    public function forget(Request $request)
    {
        //不查询数据库过滤
     $validator=Validator::make($request->all(),[
         'tel'=>[
             'regex:/^1[35897]\d{9}$/'
         ],
         'sms'=>[
             'regex:/^\d{6}$/'
         ],
         'password'=>'min:6'
     ],[
        'tel.regex'=>'手机号格式不正确',
        'sms.regex'=>'验证码错误',
         'password.min'=>'密码必须6位以上'
     ]);
     //错误逻辑
        if ($validator->fails()){
            //保存错误信息
            $errors=$validator->errors();
            //取出第一条错误信息
            $error=$errors->first();
            //返回出错误信息
            return [
                'status'=>'false',
                'message'=>$errors,
            ];
        }
        //得到用户填写的手机号对应的验证码
        $code=Redis::get('code'.$request->tel);
        //根据手机号找到对应用户失败返回 false
        $res=user::where('tel',$request->tel)->first();
        if($res==null){
            return [
              'status'=>'false',
                'message'=>'手机号码错误,请确认手机号是否正确'
            ];
        }
        //判断手机验证码是否正确
        if($code!=$request->sms){
            return [
                'status'=>'false',
                'message'=>'修改失败验证码错误',
            ];
        }else{
          //排查错误情况,修改密码
         $res->update([
            'password'=>bcrypt($request->password)
         ]);
         return ['status'=>'true','message'=>'修改密码成功'];
        }
    }
}
