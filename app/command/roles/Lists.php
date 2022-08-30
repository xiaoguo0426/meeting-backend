<?php


namespace app\command\roles;


use extension\util\zoom\Roles;
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
        $this->setName('zoom:roles')
            ->setDescription('列出所有角色');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $list = (new Roles())->lists();
        $output->writeln(var_export($list, true));
    }
}