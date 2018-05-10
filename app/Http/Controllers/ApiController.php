<?php

namespace App\Http\Controllers;

use App\SphinxClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    //店铺列表
    public function shops(Request $request)
    {

        if ($request->keyword){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
            $cl->SetLimits(0, 1000);
            $info = $request->keyword;
            $res = $cl->Query($info, 'shops');//shopstore_search
//print_r($cl);
            if ($res['total']){
                $data=collect($res['matches'])->pluck('id')->toArray();
//                dd($data);
                $res= DB::table('business_details')->whereIn('id',$data)->get();
                return json_encode($res);
            }else{
                return [];
            }
        }
        else {
            $data = Redis::get('shops');
            if ($data == false) {
                $res = DB::table('business_details')->get();
                Redis::set('shops', serialize($res), 3600);
            } else {
                $res = unserialize($data);
            }
//        dd(json_encode($res));
            return json_encode($res);
        }
    }
    //店铺内容
    public function foods(Request $request)
    {
//        /        d($request->id)
//        $redis=new \Redis();
        $data=Redis::get('foods');
//        dd($data);
        if ($data==false){
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
            Redis::set('foods',serialize($rows),3600);
        }
        else{
//            dd(1);
            $rows=unserialize($data);
        }
//        dd($rows);
        //返回数据
        return json_encode($rows);

    }

}