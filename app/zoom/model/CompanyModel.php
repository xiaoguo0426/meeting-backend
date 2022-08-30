<?php

namespace app\zoom\model;
use think\admin\Model;

class CompanyModel extends Model
{
    /**
     * 日志名称
     * @var string
     */
    protected $oplogName = '公司';

    /**
     * 日志类型
     * @var string
     */
    protected $oplogType = '公司管理';

    protected $table = 'company';
}