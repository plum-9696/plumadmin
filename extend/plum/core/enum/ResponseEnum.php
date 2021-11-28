<?php

namespace plum\core\enum;

class ResponseEnum
{
    const SUCCESS = 10000;              // 成功
    const USER_FORBIDDEN = 10001;       // 账号被禁止
    const FAILED = 10400;               // 失败
    const LOST_LOGIN = 10401;           //  登录失效
    const PERMISSION_FORBIDDEN = 10403; // 权限禁止
    const ROUTE_FAIL = 10404;           // 路由不正确
    const SERVER_FAIL = 10500;          // 服务器异常
}
