<?php

namespace plum\core\exception;

use plum\core\enum\ResponseEnum;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        Exception::class
    ];


    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 20:16
     */
    public function report(Throwable $exception): void
    {
        $this->app->log->record($exception, 'error');
    }

    /**
     * 渲染异常
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 20:32
     */
    public function render($request, Throwable $e): Response
    {
        $debug = app()->isDebug();
        //默认忽略异常配置
        $data = [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
        ];
        //路由错误
        if ($e instanceof HttpException) {
            $data['code'] = ResponseEnum::ROUTE_FAIL;
            $data['message'] = 'NOT FOUND';
        }
        if (!$this->isIgnoreReport($e) && !$debug) {
            //内部异常,且线上模式
            $data['code'] = ResponseEnum::SERVER_FAIL;
            $data['message'] = 'SERVER FAIL';
        }
        return json($data);
    }
}
