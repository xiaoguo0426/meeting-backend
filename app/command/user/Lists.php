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
        $this->setName('zoom:user')
            ->addArgument('roles_id', Argument::OPTIONAL, "role_id(从命令 php think zoom:roles 获得) 默认0")
            ->addArgument('status', Argument::OPTIONAL, "status: active,inactive,pending")
            ->setDescription('列出所有用户');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $status = $input->getArgument('status') ?? 'active';
        $role_id = $input->getArgument('roles_id') ?? '0';
        $list = (new User())->lists($role_id, $status,1,1);
        $output->writeln(var_export($list, true));
    }
}