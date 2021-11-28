<?php

namespace plum\lib\queue;

class Client extends \Workerman\RedisQueue\Client
{
    /**
     * @param $package
     */
    protected function fail($package)
    {
        //增加日志处理
        trace([
            '队列异常',
            $package
        ], 'error');
        $this->_redisSend->lPush(static::QUEUE_FAILD, \json_encode($package));
    }
}
