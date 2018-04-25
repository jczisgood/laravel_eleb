<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
//    protected $goods_list=[];
    //
    public function shops()
    {
        $res = DB::table('business_details')->get();
        return $res;
    }

    public function foods(Request $request)
    {
//        /        d($request->id);
        $user_id = $request->id;
//        dd($user_id);
        $id=DB::table('businessusers')->where('user_id',$user_id)->pluck('id');
//        dd($id);
        $rows = DB::table('businessusers')->find($id[0]);
//        dd($id[0]);
//        $rows=json_decode($rows);
//        dd($rows);
//        dd($id->id);
        $commodity=DB::table('commodities')->where('user_id',$rows->user_id)->get();
        foreach ($commodity as &$com){
            $vals= DB::select('select * from foods where commodity_id=?',[$com->id]);
            foreach ($vals as &$val){
//                dd($val);
                $val->goods_name=$val->name;
                $val->goods_id=$val->id;
                $com->goods_list=$vals;
            }
        }
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

        $rows->commodity=$commodity;
//        dd($rows);
        return json_encode($rows);

    }


    public function show()
    {
//        d($request->id);
//        /        d($request->id);
        $id = 14;
        $rows = DB::table('businessusers')->find($id);
//        $rows=json_decode($rows);
//        dd($rows);
        $commodity=DB::table('commodities')->where('user_id',$rows->user_id)->get();
        foreach ($commodity as &$com){
//            dd($com);
//            for($i=0;$i<=)
            $vals= DB::select('select * from foods where commodity_id=?',[$com->id]);
            foreach ($vals as &$val){
//                dd($val);
                $val->goods_name=$val->name;
                $com->goods_list=$vals;
            }
        }

//        dd($commodity);
//        dd(1);
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

        $rows->commodity=$commodity;
//        dd($commodity->goods_list);

        dd($rows);
        return json_encode($rows);
        }
}