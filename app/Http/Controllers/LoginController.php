<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    public function check(Request $request)
    {
        //登录
        if (Auth::attempt(['username' => $request->name, 'password' => $request->password])) {
////            $name=$request->name;
//            $validator=Validator::make($request->all(),[
//
//            ],[
//
//            ]);
            return [
                "status" => "true",
                "message" => "登录成功",
                "user_id" => Auth::user()->id,
                "username" => Auth::user()->username
            ];

        } else {
            return [
            "status"=>"false",
        "message"=>"登录失败",
        ];
        }
    }
}
