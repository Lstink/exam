<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class RegisterController extends Controller
{
    /**
     * @content 注册接口
     */
    public function register(Request $request)
    {
        $data = $request -> only(['phone','password']);
        //手机验证
        $phone = Admin::where('phone',$data['phone']) -> exists();
        if ($phone) {
            return  response() -> json(['code'=>'2','message'=>'该手机号已经被注册']);
        }
        //验证码验证
        $code = session('code')['code'];
        if ($code != $request -> code) {
            return  response() -> json(['code'=>'2','message'=>'验证码错误']);
        }
        //验证数据
        $validator = Validator::make($data, [
            'phone' => 'required|numeric|unique:admins',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return  response() -> json(['code'=>'2','message'=>'请填写着正确的格式']);
        }
        try {
            $data['password'] = Hash::make($data['password']);
            $res = Admin::create($data);
            if ($res) {
                return  response() -> json(['code'=>'1','message'=>'注册成功']);
                // return response() -> json(['data'=>$res]);
            }else{
                return  response() -> json(['code'=>'2','message'=>'注册失败']);
                // return response() -> json(['message'=>'failed']);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()]);
        }
    }
    /**
     * @content 发送验证码
     */
    public function sendCode(Request $request)
    {
        // $code = [
        //     'code'=>'785367',
        //     'phone'=>'17603843408',
        // ];
        // session(['code'=>$code]);
        // die;
        // dd(session('code'));
        $phone = $request -> phone;
        //查询数据库中是否存在该手机号
        $exists = Admin::where('phone',$phone)->exists();
        if ($exists) {
            return response()->json(['code'=>2,'message' => '该手机号已经被注册']);
        }
        //查询redis中是否存在该手机号
        $redis = Redis::get($phone);
        if (!empty($redis)) {
            return response()->json(['message' => '一分钟内只能发送一次，请稍候再试']);
        }
        $num = Redis::set($phone,'1');
        Redis::expire($phone, 60);
        //生成验证码
        $code = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
        //发送验证码
        //手机号
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "f6496824839f44b2bba9bafe8b154d95";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=".$phone."&param=code%3A".$code."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $resPhone = curl_exec($curl);
        // dump($resPhone);
        $resPhone = json_decode($resPhone);
        // dd($resPhone);
        if ($resPhone -> return_code == '00000') {
            //发送成功 存session
            $request -> session() -> forget('code');
            $code = [
                'code'=>$code,
                'phone'=>$phone,
            ];
            session(['code'=>$code]);
            echo json_encode(['code'=>1,'msg'=>'ok']);
        }else{
            echo json_encode(['code'=>0,'msg'=>'error']);
        }
    }
}
