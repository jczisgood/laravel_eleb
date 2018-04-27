<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //店铺列表
    public function shops()
    {

        $res = DB::table('business_details')->get();
        return $res;
    }

    //店铺内容
    public function foods(Request $request)
    {
//        /        d($request->id)
        //得到店铺id
        $user_id = $request->id;
//        dd($user_id);
        //根据店铺id找到账号id
        $id=DB::table('businessusers')->where('user_id',$user_id)->pluck('id');
        //根据id找到
        $rows = DB::table('businessusers')->find($id[0]);
//        dd($id[0]);
//        $rows=json_decode($rows);
//        dd($rows);
        $commodity=DB::table('commodities')->where('user_id',$rows->user_id)->get();
        foreach ($commodity as &$com){
            $vals= DB::select('select * from foods where commodity_id=?',[$com->id]);
            foreach ($vals as &$val){
//                dd($val);
                //修改字段
                $val->goods_name=$val->name;
                $val->goods_id=$val->id;
                $com->goods_list=$vals;
            }
        }
        //添加评论假数据
        $rows->evaluate = [
            [
                'user_id' => '1223321',
                'username' => '二十',
                'user_img' => '',
                'time' => time(),
                'evaluate_code' => '',
                'send_time' => '23',
                'evaluate_details' => '']
        ];
        //加入食品分类和食品内容
        $rows->commodity=$commodity;
//        dd($rows);
        //返回数据
        return json_encode($rows);

    }

}