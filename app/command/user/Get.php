<?php


namespace app\command\user;


use extension\util\zoom\User;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class Get extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:users:get')
            ->addArgument('emailOrUserId', Argument::REQUIRED, "email或user_id")
            ->setDescription('获取用户详情');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $emailOrUserId = $input->getArgument('emailOrUserId') ?? '';
        $list = (new User())->get($emailOrUserId);
        $output->writeln(var_export($list, true));
    }
}