<?php

namespace plum\core;

use think\facade\Cache;
use Workerman\RedisQueue\Client;

abstract class Queue
{
    abstract public function handler($data);

    abstract public function name(): string;

    public function send($data, $delay = 0)
    {
        $redis = Cache::store('redis')->handler();
        $queue_waiting = Client::QUEUE_WAITING;
        $queue_delay = Client::QUEUE_DELAYED;
        $now = time();
        $package_str = json_encode([
            'id'       => rand(),
            'time'     => $now,
            'delay'    => 0,
            'attempts' => 0,
            'queue'    => $this->name(),
            'data'     => $data
        ]);
        if ($delay) {
            return $redis->zAdd($queue_delay, $now + $delay, $package_str);
        }
        return $redis->lPush($queue_waiting . $this->name(), $package_str);
    }
}
