<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class RegistController extends Controller
{
    //
    public function save(Request $request)
    {
        //得到验证码
        $code=Redis::get('code'.$request->tel);
        //表单验证
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:20|unique:users',
            'tel' => 'required|unique:users',
        ]
        ,[
            'username.required'=>'用户名必填',
            'username.unique'=>'用户名已存在',
            'username.max'=>'用户名不能大于20位',
            'tel.required'=>'手机号必填',
            'tel.unique'=>'手机号已存在',
            ]);
        if ($validator->fails()) {
            //保存错误信息
            $errors=$validator->errors();
            //取出错误第一条
            return['status'=>'false','message'=>$errors->first()];
        }
            //判断验证码是否正确
        if($request->sms!=$code){
         return ['status'=>'false','message'=>'验证码不正确'];
        }else{
           //成功保存数据库
            $user=new User();
               $user->username=$request->username;
                $user->password=bcrypt($request->password);
                $user->tel=$request->tel;
                $user->save();

            return ['status'=>"true",'message'=>'注册成功'];
        }
    }
}
