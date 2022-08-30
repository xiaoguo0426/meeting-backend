<?php


namespace app\command\meeting;


use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Create extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:meeting:create')
            ->setDescription('创建会议');
    }

    protected function execute(Input $input, Output $output)
    {

    }
}