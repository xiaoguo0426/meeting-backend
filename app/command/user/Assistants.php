<?php


namespace app\command\user;


use extension\util\zoom\User;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class Assistants extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:users:assistants')
            ->addArgument('user_id', Argument::OPTIONAL, "")
            ->setDescription('列出所有助手');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $user_id = $input->getArgument('user_id') ?? '';
        $list = (new User())->assistants($user_id);
        $output->writeln(var_export($list, true));
    }
}