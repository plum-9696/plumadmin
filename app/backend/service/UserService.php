<?php

namespace app\backend\service;

use app\model\UserModel;
use app\model\UserRoleModel;
use think\Exception;
use think\facade\Db;

class UserService
{
    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:39
     */
    public static function create($data)
    {
        try {
            Db::startTrans();
            //创建用户
            $user = UserModel::create($data);
            //关联角色
            $user->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:39
     */
    public static function update($data)
    {
        try {
            Db::startTrans();
            //创建用户
            $user = UserModel::update($data);
            //删除关联表,重新创建关联角色
            UserRoleModel::where('user_id', $data['id'])->delete();
            $user->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }
}
