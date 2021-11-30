<?php

namespace plum\core\base;

use plum\helper\Helper;
use plum\lib\Token;
use think\exception\HttpException;
use think\Model;

abstract class AuthController extends Controller
{
    protected $auth;
    protected $userinfo;

    /**
     * 解析文档
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 16:20
     */
    protected function parseDoc()
    {
        $doc = Helper::getNotes(static::class, $this->request->action());
        $methods = ['POST', 'GET', 'DELETE', 'PATCH', 'PUT'];
        //获取请求方法
        if (isset($doc['method']) && in_array(strtoupper($doc['method']), $methods)) {
            $this->method = strtoupper($doc['method']);
        } else {
            $this->method = 'ANY';
        }

        //是否登录
        if (isset($doc['auth']) && strtolower($doc['auth']) === 'false') {
            $this->auth = false;
        } else {
            $this->auth = true;
        }
    }

    /**
     * 校验路由类型是否正确
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月27日 16:10
     */
    protected function checkRoute()
    {
        if ($this->method !== 'ANY' && $this->request->method() !== $this->method) {
            throw new HttpException(404);
        }
    }

    /**
     * 中间件变量
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月29日 17:24
     */
    protected function setParam()
    {
        //设置请求变量
        $this->data = $this->request->param();
        if ($this->method !== 'ANY') {
            $method = strtolower($this->method);
            $this->request->$method();
        }
        $this->request->withMiddleware([
            'data'     => $this->data,
            'userinfo' => $this->userinfo
        ]);
    }

    /**
     * 校验
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 17:17
     */
    protected function checkLogin()
    {
        //白名单之内的不需要登录
        if ($this->auth) {
            //调试模式,可以直接使用uuid
            if ($this->app->isDebug() && $this->request->param('uuid')) {
                $id = $this->request->param('uuid');
            } else {
                $id = Token::auth();
            }
            $this->userinfo = $this->getUserinfo($id);
            //校验权限
            $this->checkPermission();
        }
    }

    /**
     * 校验权限
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月26日 12:06
     */
    abstract protected function checkPermission();

    /**
     * 获取用户信息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 17:17
     */
    abstract public function getUserinfo($id): Model;

    // 初始化
    protected function initialize()
    {
        //解析文档
        $this->parseDoc();
        //校验路由是否合规
        $this->checkRoute();
        //校验登录
        $this->checkLogin();
        //设置变量,以便其他应用调用
        $this->setParam();
    }
}
