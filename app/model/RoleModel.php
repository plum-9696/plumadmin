<?php

namespace app\model;

use plum\core\base\Model;
use think\model\concern\SoftDelete;

class RoleModel extends Model
{
    use SoftDelete;

    protected $name = 'role';

    /**
     * 多对多关联 rule
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:41
     */
    public function rule()
    {
        return $this->belongsToMany(RuleModel::class, RoleRuleModel::class, 'rule_id', 'role_id');
    }
}
