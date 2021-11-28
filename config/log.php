<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    // 默认日志记录通道
    'default'      => env('log.channel', 'pretty_file'),
    // 日志记录级别
    'level'        => ['error', 'debug', 'sql'],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [
        'error' => ['email', 'pretty_file'],
        'debug' => ['pretty_file'],
        'sql'   => ['pretty_file'],
    ],
    // 关闭全局日志写入
    'close'        => false,
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        //默认tp的日志
        'file'        => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => '',
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => [],
            // 最大日志文件数量
            'max_files'      => 0,
            // 使用JSON格式记录
            'json'           => false,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => true,
            // 日志输出格式化
            'format'         => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
            'time_format'    => 'Y-m-d H:i:s',
            'file_size'      => 1024 * 1024 * 10,
        ],
        'pretty_file' => [
            // 日志记录方式
            'type'           => \plum\core\log\File::class,
            // 日志保存目录
            'path'           => '',
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => [],
            // 最大日志文件数量
            'max_files'      => 30,
            //日志大小
            'file_size'      => 1024 * 1024 * 10,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => false,
            // 日志时间格式
            'time_format'    => 'Y-m-d H:i:s',
            // 是否实时写入
            'realtime_write' => false,
        ],
        'email'       => [
            //日志的记录方式,只针对异常类型ERROR
            'type'        => \plum\core\log\Email::class,
            //项目名称,用于邮件标题区分项目
            'title'       => env('APP_NAME', '项目名称'),
            //是否关闭,调试模式下关闭,生产模式下打开
            'close'       => env('APP_DEBUG', true),
            //调试模式,只有需要校验邮箱是否连同,才开启
            //只要trace('错误日志','error'),写入了内存,就会调用,邮件成功与否都会打印出来
            'debug'       => false,
            //SMTP端口
            'port'        =>  env('stmp.port', 25),
            //SMTP服务器地址
            'host'        => env('stmp.host', ''),
            //发件人昵称
            'sender_name' => env('stmp.sender_name', ''),
            //SMTP用户名,一般指邮箱用户名
            'username'    => env('stmp.username', ''),
            //SMTP密码
            'password'    => env('stmp.password', ''),
            //收件邮箱地址
            'address'     => env('stmp.address', [])
        ]
    ],

];
