<?php


namespace app\command\meeting;


use extension\util\zoom\Meeting;
use extension\util\zoom\ZoomException;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Get extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:meeting')
            ->addArgument('id', Argument::REQUIRED, "meeting id")
            ->setDescription('获取一个会议详情');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $id = $input->getArgument('id') ?? '';

        try {
            $meeting = (new Meeting())->get($id);
            $output->writeln(var_export($meeting, true));
        } catch (ZoomException $exception) {
            $output->error('Meeting ID:' . $id . ' code：' . $exception->getCode() . ' error:' . $exception->getMessage());
        }
    }
}