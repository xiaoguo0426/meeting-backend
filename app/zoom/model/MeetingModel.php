<?php

namespace app\zoom\model;
use think\admin\Model;

class MeetingModel extends Model
{
    /**
     * 日志名称
     * @var string
     */
    protected $oplogName = '会议';

    /**
     * 日志类型
     * @var string
     */
    protected $oplogType = '会议管理';

    protected $table = 'meeting';
}