<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Exception $exception)
    // {
    //     //如果$exception 是 ApiException的一个实例，则自定义返回的错误信息
    //     if($exception instanceof ApiException){
    //         $result = [
    //             "msg"=>$exception->getMessage(),
    //             "data"=>""
    //         ];
    //         return response()->json($result,422);
    //     }
    //     //如果不是，则使用 父类的处理方法

    //     return parent::render($request, $exception);
    // }
    public function render($request, Exception $e)
    {

        if (config('app.debug')) {
            return parent::render($request, $e);
        }
        return $this->handle($request, $e);
    }

    public function handle($request, Exception $e)
    {
        if ($e instanceof ApiException) {
            $result = [
                "msg" => "",
                "data" => $e->getMessage(),
                "status" => 0
            ];
            return response()->json($result);
        }

        return parent::render($request, $e);
    }

}
