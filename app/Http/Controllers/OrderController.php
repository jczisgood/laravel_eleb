<?php

namespace App\Http\Controllers;

use App\Address;
use App\Order;
use App\OrderGoods;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //存入信息
    public function create(Request $request)
    {

        //得到地址
       $address=Address::find($request->address_id);
//       dd($address);
        //查到购物车里面的食品id
        $row=DB::table('cates')->where('user_id',Auth::user()->id)->first();
//        dd($row)
        //根据食品id查找到对应食品
        $val=DB::table('foods')->where('id',$row->goodsList)->first();
        //根据食品找到对应的分类
        $va=DB::table('commodities')->where('id',$val->commodity_id)->first();
        //根据分类id找到商铺信息
        $shops=DB::table('business_details')->where('id',$va->user_id)->first();
//        dd($shops);
        //查询购物车表中的数据
        $cates=DB::table('cates')->where('user_id',Auth::user()->id)->get();
        //根据商品id来查询价格和数量
        $money=0;
        foreach($cates as $val){
           //找到食品表里的价格
           $food= DB::table('foods')->where('id',$val->goodsList)->first();
        $money+=$food->goods_price*$val->goodsCount;
        }
//        dd($price);
        DB::transaction(function ()use ($address,$shops,$money){

            $a = Order::create([
                'receipt_provence'=>$address->provence,
                'order_code'=>date('Y-m-d').uniqid(),
                'receipt_city'=>$address->city,
                'receipt_area'=>$address->area,
                'receipt_detail_address'=>$address->detail_address,
                'receipt_tel'=>Auth::user()->tel,
                'receipt_name'=>Auth::user()->username,
                'shop_id'=>$shops->id,
                'shop_name'=>$shops->shop_name,
                'shop_img'=>$shops->shop_img,
                'order_price'=>$money,
                'users_id'=>Auth::user()->id
            ]);
            $_SESSION['order_id']=$a->id;
            //查询订单表 来得到购买的食品
            $cates=DB::table('cates')->where('user_id',Auth::user()->id)->get();
//                dd($cates);
            foreach($cates as $cate){
                //食品名称变动量
                $row=DB::table('foods')->find($cate->goodsList);
                OrderGoods::create([
                        'order_id'=>$a->id,
                        'goods_id'=>$cate->goodsList,
                        'goods_name'=>$row->name,
                        'goods_img'=>$row->goods_img,
                        'amount'=>$cate->goodsCount,
                        'goods_price'=>$row->goods_price,
                ]);
            }
        });
        return ['status'=>'true','message'=>'添加成功','order_id'=>$_SESSION['order_id']];
    }

    public function index(Request $request)
    {
        //得到订单id
        $order_id=$request->id;
        //得到订单详情
        $row=DB::table('orders')->find($order_id);

//        foreach ($row as $v){
            $row->order_birth_time=date('Y-m-d H:i:s',+8*3600);
            $row->order_status=$row->order_status==0?'代付款':'已付款';
//        }
//        dd($row);
        $goods=DB::table('order_goods')->where('order_id',$row->id)->get();
        $row->goods_list=$goods;
        $home=$row->receipt_provence.' '.$row->receipt_city.' '.$row->receipt_area.' '.$row->receipt_detail_address.' '.$row->receipt_name.' '.$row->receipt_tel;
        $row->order_address=$home;
        return json_encode($row);
    }

    public function order()
    {
//        dd(1);
        //查询orders表
        $rows=DB::table('orders')->where('users_id',Auth::user()->id)->get();
//        dd($rows);
        foreach ($rows as $row){
            $row->order_birth_time=date('Y-m-d H:i:s');
            $row->order_status=$row->order_status==0?'代付款':'已付款';
            $goods=DB::table('order_goods')->where('order_id',$row->id)->get();
            $row->goods_list=$goods;
            $home=$row->receipt_provence.' '.$row->receipt_city.' '.$row->receipt_area.' '.$row->receipt_detail_address.' '.$row->receipt_name.' '.$row->receipt_tel;
            $row->order_address=$home;
        }
        return json_encode($rows);
    }
}
