<?php


namespace app\command\meeting;


use extension\util\zoom\Meeting;
use extension\util\zoom\ZoomException;
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
            ->addArgument('status', Argument::REQUIRED, "status end/recover  只能结束正在进行中的会议或恢复已删除的会议")
            ->setDescription('更新会议');
    }

    protected function execute(Input $input, Output $output)
    {
        $id = $input->getArgument('id') ?? '';
        $status = $input->getArgument('status') ?? 'end';

        try {
            $update = (new Meeting())->status($id, $status);
            $output->writeln('Meeting ID:' . $id . ' Update Status:' . $update);
        } catch (ZoomException $exception) {
            $output->error('Meeting ID:' . $id . ' code：' . $exception->getCode() . ' error:' . $exception->getMessage());
        }
    }
}