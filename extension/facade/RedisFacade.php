<?php

namespace extension\facade;

use extension\util\redis\Redis;
use think\Facade;

class RedisFacade extends Facade
{
    private static $_instance = [];

    protected static function getFacadeClass()
    {
        return Redis::class;
    }

    public static function instance(...$args)
    {
        $pool = $args[0] ?? 'default';
        $instance = self::$_instance[$pool] ?? null;
        if (is_null($instance)) {
            $instance = parent::createFacade('', [$pool], true);
            self::$_instance[$pool] = $instance;
        }
        return $instance;
    }
}