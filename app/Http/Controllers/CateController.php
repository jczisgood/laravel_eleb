<?php

namespace App\Http\Controllers;

use App\Cate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CateController extends Controller
{
    //保存用户购买的订单到数据库
    public function store(Request $request)
    {
        DB::delete("delete from cates");
//        dd(Auth::user()->id);
        //goodsList 食品id
//        dd($request->goodsList);
        //goodsCount 食购买品的数量
//        dd ($request->goodsCount);
        $goodscount=$request->goodsCount;

        $goodslist=$request->goodsList;
//        foreach ($goodscount as $key=>$value){
//            dump($key);
//            dump($value);
//        }
//        $num = 0;
        //遍历得到对应食品=>数量


        foreach ($goodscount as $key=>$val){
//           $goodscount->$key;
//           dd($goodscount[$key]);
           Cate::create([
               'goodsCount'=>$val,
               'goodsList'=>$goodslist[$key],
               'user_id'=>Auth::user()->id,
           ]);
//            $num += $val;
       }
//       dd($num);
//        Cate::create([
//            'goodsCount'=>$num,
//            'goodsList'=>$goodslist[$key],
//            'user_id'=>Auth::user()->id,
//        ]);
        return ['status'=>'true','message'=>'订单成功'];
    }

    public function index()
    {
        //删除之前的数据
//        $time=time();
//        DB::delete("delete from cates where created_at<'{$time}'");
        $rows=DB::table('cates')->where('user_id',Auth::user()->id)->where('status',0)->get();
//       dd($rows);
//        $num=0;
//        foreach ($rows as $row){
//            $row->
//        }
        $totalCost=0;
        foreach ($rows as $row){
           $food=DB::table('foods')->where('id',$row->goodsList)->first();
           $row->goods_id=$food->id;
           $row->goods_name=$food->name;
           $row->amount=$row->goodsCount;
           $row->goods_img=$food->goods_img;
           $row->goods_price=$food->goods_price;
          $totalCost+=$row->amount*$row->goods_price;
        }
    $goods['goods_list']=$rows;
        $goods['totalCost']=$totalCost;
        return $goods;
    }
}
