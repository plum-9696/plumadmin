<?php

namespace plum\lib\queue;

class CreateQueue
{
    public static function instance(): Client
    {
        $host = env('redis.host', '127.0.0.1');
        $port = env('redis.port', 6379);
        $auth = env('redis.password', '');
        $db = env('redis.select', 0);
        return new Client("redis://{$host}:{$port}", [
            'auth' => $auth,
            'db'   => $db
        ]);
    }
}
