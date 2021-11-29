<?php

namespace plum\core;

use plum\core\command\TimerCommand;

class Service extends \think\Service
{
    /**
     * 服务注册
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月22日 13:18
     */
    public function register()
    {
        //注册异常
        $this->registerException();
        //注册query
        $this->registerQuery();
        //注册验证器
        $this->registerValidate();
        //注册command
        $this->registerCommand();
    }

    /**
     * 服务启动
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月22日 13:19
     */
    public function boot()
    {
        //跨域配置
        $this->allowCrossDomain();
        //记录日志
        $this->recordLog();
    }

    /**
     * 记录日志
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 13:39
     */
    public function recordLog()
    {
        //调试模式下,记录每一个请求方便开发查错
        if(env('app_debug')){
            trace("record log",'debug');
        }
    }

    /**
     * 跨域配置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年06月08日 22:38
     */
    public function allowCrossDomain()
    {
        header('Access-Control-Allow-Credentials:true');
        header('Access-Control-Max-Age:1800');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers:Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, access-token');
        header("Access-Control-Allow-Origin:" . env('CROSS_DOMAIN', '*'));
    }

    /**
     * 注册异常
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年06月08日 12:04
     */
    public function registerException()
    {
        $this->app->bind(\think\exception\Handle::class, \plum\core\exception\ExceptionHandle::class);
    }

    /**
     * 注册query
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年06月09日 18:01
     */
    public function registerQuery()
    {
        $connections = $this->app->config->get('database.connections');
        // 支持多数据库配置注入 Query
        foreach ($connections as &$connection) {
            $connection['query'] = Query::class;
        }
        $this->app->config->set([
            'connections' => $connections
        ], 'database');
    }

    /**
     * 注册验证器
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月28日 16:52
     */
    public function registerValidate()
    {
        //反射
        \think\facade\Validate::maker(function ($validate) {
            $function = config('validate');
            foreach ($function as $name => $func) {
                $validate->extend($name, $func);
            }
        });
    }

    public function registerCommand()
    {
        $this->commands([
            'worker:timer' => TimerCommand::class
        ]);
    }
}
