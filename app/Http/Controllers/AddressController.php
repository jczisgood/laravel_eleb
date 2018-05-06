<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //保存收货地址
    public function store(Request $request)
    {
//        dd(Auth::user()->id);
//        echo 1;die;
        //表单逻辑验证
        $validator=Validator::make($request->all(),[
            'name'=>'max:20',
            'tel'=>[
                'regex:/^1[3589]\d{9}$/'
            ],
        ],[
            'name.max'=>'名称过长',
            'tel.regex'=>'电话号码格式不正确',
        ]);
        if ($validator->fails()){
            //取出错误信息
            $errors=$validator->errors();
            //弹出错误信息
            return [
              'status'=>'false',
              'message'=>$errors->first(),
            ];
        }
        //添加
        Address::create([
                'name'=>$request->name,
                'tel'=>$request->tel,
                'provence'=>$request->provence,
                'city'=>$request->city,
                'area'=>$request->area,
                'detail_address'=>$request->detail_address,
                'user_id'=>Auth::user()->id,
            ]);
        //弹出状态
        return [
            'status'=>'true',
            'message'=>'添加成功',
        ];
    }

    public function index()
    {
//        echo 1;die;
        //查询出当前用户的数据
//        dd(Auth::user()->id);
        $rows=DB::table('addresses')->where('user_id',Auth::user()->id)->get();
//        dd($rows);
        //返回数据
        return json_encode($rows);
    }

    public function edit(Request $request)
    {
        $id=$request->id;
//        dd($id);
        $row= DB::table('addresses')->find($id);
//    dd($row);
        return json_encode($row);
    }

    public function update(Request $request)
    {
        //逻辑判断
        $validator=Validator::make($request->all(),[
            'tel'=>[
                'regex:/^1[35789]\d{9}$/'
            ]
        ],[
            'tel.regex'=>'手机号格式不正确'
        ]);
        //判断有无错误
        if ($validator->fails()){
            //保存错误
            $errors=$validator->errors();
            return ['status'=>'false','message'=>$errors->first()
            ];
        }
        //查询当前的地址
        $address=Address::where('id',$request->id);
//        dd($address);
        //根据页面数据修改值
        $address->update([
            'name'=>$request->name,
            'tel'=>$request->tel,
            'provence'=>$request->provence,
            'city'=>$request->city,
            'area'=>$request->area,
            'detail_address'=>$request->detail_address,
        ]);
        //弹出状态
        return [
            'status'=>'true',
            'message'=>'修改成功',
        ];
    }

}
