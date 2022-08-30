<?php


namespace app\command\user;


use extension\util\zoom\User;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class Lists extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:user:list')
            ->addArgument('status', Argument::OPTIONAL, "status 会议类型: active,inactive,pending")
            ->setDescription('列出所有用户');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $status = $input->getArgument('status') ?? 'active';
        $list = (new User())->lists($status);
        $output->writeln(var_export($list, true));
    }
}