<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //查询所有用户分页
        try {
            $data = Admin::paginate(2);
            if ($data) {
                return response() -> json(['data'=>$data]);
            }else{
                return response() -> json(['message'=>'empty',204]);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()],500);
        }
       
       return response() -> json(['data'=>$data],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证数据
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:posts|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response() -> json(['message'=>'data error'],407);
        }
        $data = request() -> all();
        //给密码加密
        $data['password'] = Hash::make($data['password']);
        //产生一个随机的token
        
        $data['api_token'] = Str::random(60);
        try {
            $res = Admin::create($data);
            if ($res) {
                return response() -> json(['data'=>$res]);
            }else{
                return response() -> json(['message'=>'failed'],407);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()],500);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //获取主键
            $data = Admin::find($id);
            if ($data) {
                return response() -> json(['data'=>$data],200);
            }else{
                return response() -> json(['message'=>'empty'],204);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()],500);
        }
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request -> all();
        try {
            $obj = Admin::find($id);
            $obj->username = $data['username'];
            $obj->password = Hash::make($data['password']);
            //重新生成token
            $obj->token = Str::random(60);
            $res = $obj->save();
            if ($res) {
                return response() -> json(['message'=>'success']);
            }else{
                return response() -> json(['message'=>'failed'],407);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $res = Admin::destroy($id);
            if ($res) {
                return response() -> json(['message'=>'success']);
            }else{
                return response() -> json(['message'=>'failed'],407);
            }
        } catch (\Throwable $th) {
            return response() -> json(['message'=>$th -> getMessage()],500);
        }
    }
}
