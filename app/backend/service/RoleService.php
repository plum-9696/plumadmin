<?php

namespace app\backend\service;

use app\model\RoleModel;
use app\model\RoleRuleModel;
use think\Exception;
use think\facade\Db;

class RoleService
{
    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:43
     */
    public static function create($data)
    {
        try {
            Db::startTrans();
            //创建角色
            $role = RoleModel::create($data);
            //关联添加规则
            $role->rule()->saveAll($data['rule_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }

    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:46
     */
    public static function update($data)
    {
        try {
            Db::startTrans();
            //更新角色
            $role = RoleModel::create($data);
            //删除当前关联规则,重新添加
            RoleRuleModel::where('role_id', $data['id'])->delete();
            $role->rule()->saveAll($data['rule_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }
}
