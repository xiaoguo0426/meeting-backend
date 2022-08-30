<?php

namespace app\command\meeting;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use extension\util\zoom\Meeting;

class Lists extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:meeting:list')
            ->addArgument('type', Argument::OPTIONAL, "type 会议类型: live,scheduled,upcoming,upcoming_meetings,previous_meetings")
            ->setDescription('列出所有会议');
    }

    /**
     * @throws \extension\util\zoom\ZoomException
     */
    protected function execute(Input $input, Output $output)
    {
        $type = $input->getArgument('type') ?? '';
        $list = (new Meeting())->lists($type);
        $output->writeln(var_export($list, true));
    }
}