<?php

namespace plum\core\base;

use think\App;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Validate;
use plum\core\traits\ResponseTrait;
use plum\helper\Helper;

abstract class Controller
{
    use ResponseTrait;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];


    protected $data;
    protected $method;


    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

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
            'data' => $this->data
        ]);
    }

    // 初始化
    protected function initialize()
    {
        //解析文档
        $this->parseDoc();
        //校验路由是否合规
        $this->checkRoute();
        //设置变量,以便其他应用调用
        $this->setParam();
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @throws ValidateException
     * @return array|string|true
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
