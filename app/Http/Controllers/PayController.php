<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PayController extends Controller
{
    //
    public function send()
    {
//        dd(1);
        $order_id=25;
        //给用户发短信给商家发邮件
        $order=Order::find($order_id);
        $order->update([
            'order_status'=>1,
        ]);
        $params = array ();
        // *** 需用户填写部分 ***
        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "LTAIpRDhsMZCuFq2";
        $accessKeySecret = "bMGlY2BkOKs8WPKGb7fxW0ysQUNbQL";

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $order->receipt_tel;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "大诚烧烤";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_133979547";

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "name" => $order->receipt_name,
//            "product" => "阿里通信"
        );
        // fixme 可选: 设置发送短信流水号
//        $params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
//        $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelperController();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        // fixme 选填: 启用https
        // ,true
        );
        dd($content->Message);

        $businessusers=DB::table('businessusers')->where('user_id',$order->shop_id)->first();
//        dd($businessuser->status);
        $email=$businessusers->email;
//        dd($email);
        Mail::send(
            'b.index',//邮件视图模板
            ['name'=>$businessusers->name],
            function ($message)use($email){
                $message->to($email)->subject('订单确认');
//                dd($email);
            }
        );
        return [
          'status'=>'true',
          'message'=>'支付成功',
        ];
          }
}
