<?php

namespace plum\core\command;

use plum\helper\Helper;
use plum\lib\queue\CreateQueue;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Lib\Timer;
use Workerman\Worker;

class TimerCommand extends Command
{
    public function configure()
    {
        $this->setName('worker:timer')
            ->addArgument('action', Argument::OPTIONAL, 'start|stop|restart|reload|status', 'start')
            ->addOption('daemon', 'd', Option::VALUE_NONE, 'Run the workerman server in daemon mode.')
            ->setDescription('timer');
    }

    public function execute(Input $input, Output $output)
    {
        $worker = new Worker();
        $action = $input->getArgument('action');
        $daemon = $input->getOptions('daemon');
        //如果在linux系统下,改造一下argv参数,好兼容workerman
        if (Helper::isLinux()) {
            global $argv;
            $argv = [];
            array_unshift($argv, 'think', $action);
            if ($daemon) {
                $argv[] = '-d';
            }
        }

        $worker->onWorkerStart = function (Worker $task) {
            $timer = config('timer');
            $queue = config('queue');
            //运行定时器
            if (count($timer) > 0) {
                foreach ($timer as $item) {
                    //一秒检查一次
                    $obj = app()->make($item);
                    Timer::add(1, function () use ($obj) {
                        call_user_func_array([$obj, 'execute'], []);
                    });
                }
            }
            //队列的订阅
            foreach ($queue as $item) {
                $obj = app()->make($item);
                CreateQueue::instance()->subscribe($obj->name(), function ($data) use ($obj) {
                    call_user_func_array([$obj, 'handler'], [$data]);
                });
            }
        };

        Worker::runAll();
    }
}
