<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use plum\helper\Helper;

class Common extends AuthBackendController
{
    /**
     * 获取所有的路由
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 11:56
     */
    public function routes()
    {
        //过滤的方法,注意小写
        $except = ['__construct','rendersuccess','renderpage'];
        $namespace = (new \ReflectionClass(__CLASS__))->getNamespaceName();
        //获取所有类库文件
        $paths = Helper::getAllFile(__DIR__, 'php');
        //获取当前的控制器
        $routes = array_map(function ($item) use ($namespace, $except) {
            //去除头部
            $item = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', strstr($item, '.php', true));
            //获取所有方法
            $methods = (new \ReflectionClass($namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $item)))
                ->getMethods(\ReflectionMethod::IS_PUBLIC);
            //过滤不需要的方法
            $methods = array_filter(array_map(function ($item) {
                return strtolower($item->getName());
            }, $methods), function ($item) use ($except) {
                return !in_array($item, $except);
            });
            return [
                str_replace(DIRECTORY_SEPARATOR, '.', strtolower($item)) => array_values($methods)
            ];
        }, $paths);
        return $this->renderSuccess($routes);
    }
}
