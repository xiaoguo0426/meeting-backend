<?php

namespace app\zoom\model;
use think\admin\Model;

class UserModel extends Model
{
    /**
     * 日志名称
     * @var string
     */
    protected $oplogName = '会议人员';

    /**
     * 日志类型
     * @var string
     */
    protected $oplogType = '会议人员管理';

    protected $table = 'plan_user';
}