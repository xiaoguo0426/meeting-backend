<?php


namespace app\admin\model;


use think\admin\Model;
use think\admin\model\SystemBase;
use think\model\relation\HasOne;

/**
 * 系统用户模型
 * Class SystemUser
 * @package think\admin\model
 */
class SystemUser extends Model
{
    /**
     * 日志名称
     * @var string
     */
    protected $oplogName = '系统用户';

    /**
     * 日志类型
     * @var string
     */
    protected $oplogType = '系统用户管理';

    /**
     * 获取用户数据
     * @param mixed $map 数据查询规则
     * @param array $data 用户数据集合
     * @param string $field 原外连字段
     * @param string $target 关联目标字段
     * @param string $fields 关联数据字段
     * @return array
     */
    public static function items($map, array &$data = [], string $field = 'uuid', string $target = 'user_info', string $fields = 'username,nickname,headimg,status,is_deleted'): array
    {
        $query = static::mk()->where($map)->order('sort desc,id desc');
        if (count($data) > 0) {
            $users = $query->whereIn('id', array_unique(array_column($data, $field)))->column($fields, 'id');
            foreach ($data as &$vo) $vo[$target] = $users[$vo[$field]] ?? [];
            return $users;
        } else {
            return $query->column($fields, 'id');
        }
    }

    /**
     * 关联身份权限
     * @return HasOne
     */
    public function userinfo(): HasOne
    {
        return $this->hasOne(SystemBase::class, 'code', 'usertype')->where([
            'type' => '身份权限', 'status' => 1, 'deleted' => 0,
        ]);
    }

    /**
     * 默认头像处理
     * @param mixed $value
     * @return string
     */
    public function getHeadimgAttr($value): string
    {
        if (empty($value)) try {
            $host = sysconf('base.site_host') ?: 'https://v6.thinkadmin.top';
            return "{$host}/static/theme/img/headimg.png";
        } catch (\Exception $exception) {
            return "https://v6.thinkadmin.top/static/theme/img/headimg.png";
        } else {
            return $value;
        }
    }
}