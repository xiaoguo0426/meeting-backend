<?php


namespace app\command\meeting;


use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Status extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:meeting:update')
            ->addArgument('id', Argument::REQUIRED, "meeting id")
            ->addArgument('status', Argument::REQUIRED, "status e.g.:end,recover")
            ->setDescription('更新会议');
    }

    protected function execute(Input $input, Output $output)
    {

    }
}