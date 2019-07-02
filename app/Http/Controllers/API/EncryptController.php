<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EncryptController extends Controller
{
    /**
     * @content 模拟前台数据的加密
     */
    public function openEncrypt()
    {
        $arr = [
            'timestamps'=>'1562033323',
            'nonceStr'=>'fs6s5df5hta',
            'phone'=>'17603843408',
            'password'=>'12345678'
        ];
        foreach ($arr as $key => $value) {
            $arr[$key] = encryptCBC($value);
        }
        dd($arr);
    }
}

