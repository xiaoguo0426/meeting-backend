<?php

namespace app\command\meeting;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use extension\util\zoom\Meeting;

class User extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:meeting:user')
            ->addArgument('user_id', Argument::REQUIRED, "user_id或者邮箱")
            ->addArgument('type', Argument::OPTIONAL, "type 会议类型: live,scheduled,upcoming,upcoming_meetings,previous_meetings")
            ->setDescription('列出指定用户的所有会议');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $user_id = $input->getArgument('user_id') ?? '';
        $type = $input->getArgument('type') ?? '';
        $list = (new Meeting())->user($user_id, $type);
        $output->writeln(var_export($list, true));
    }
}