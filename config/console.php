<?php

use app\command\meeting\Create;
use app\command\meeting\Delete;
use app\command\meeting\Get;
use app\command\meeting\Lists;
use app\command\meeting\Status;
use app\command\meeting\User;
use app\command\Oauth;
use app\command\Redis;
use app\command\user\Assistants;

return [
    'commands' => [
        'redis' => Redis::class,
        'zoom:oauth' => Oauth::class,
        'zoom:meeting' => Get::class,
        'zoom:meeting:list' => Lists::class,
        'zoom:meeting:user' => User::class,
        'zoom:meeting:create' => Create::class,
        //        'zoom:meeting:update' => Update::class,
        'zoom:meeting:delete' => Delete::class,
        'zoom:meeting:status' => Status::class,
        'zoom:users' => \app\command\user\Lists::class,
        'zoom:users:get' => \app\command\user\Get::class,
        'zoom:users:assistants' => Assistants::class,
        'zoom:roles' => \app\command\roles\Lists::class,

    ]
];