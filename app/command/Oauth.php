<?php


namespace app\command;


use GuzzleHttp\Exception\GuzzleException;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use extension\util\zoom\AccessToken;

class Oauth extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('zoom:oauth')
            ->setDescription('刷新AccessToken');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(Input $input, Output $output)
    {
        try {
            $oauth = (new AccessToken())->oauth();
            $output->writeln(var_export($oauth, true));
        } catch (GuzzleException $exception) {
            $output->error($exception->getMessage());
        }
    }
}