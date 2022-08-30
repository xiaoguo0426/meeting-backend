<?php


namespace app\command;


use extension\facade\RedisFacade;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Redis extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('redis')
            ->setDescription('测试Redis');
    }

    protected function execute(Input $input, Output $output)
    {
        $redis = RedisFacade::instance();
        $redis->set('aaa0',33);
        var_dump($redis->get('aaa0'));
//
//        $my = RedisFacade::instance('my');
//
//        $my->set('aaa1',3333);
//        var_dump($my->get('aaa1'));
//
//        $redis->set('bbb0',4444);
//        $my->set('bbb1',5555);
    }
}