<?php

namespace app\backend\controller;

use app\backend\service\RoleService;
use app\controller\AuthBackendController;
use app\model\RoleModel;
use plum\helper\Arr;

class Role extends AuthBackendController
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 18:07
     */
    public function page()
    {
        $page = RoleModel::autoOrder()
            ->autoSearch()
            ->paginate();
        return $this->renderPage($page);
    }

    /**
     * 列表
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:48
     */
    public function list()
    {
        $list = RoleModel::order(['sort', 'create_time', 'id'])
            ->select();
        return $this->renderSuccess($list);
    }

    /**
     * 详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:50
     */
    public function detail()
    {
        if (!$role = RoleModel::find($this->data['id'] ?? 0))
            error('角色不存在');
        $role['rule_ids'] = Arr::pluck($role->rule, 'id');
        $role->hidden(['rule']);
        return $this->renderSuccess($role);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:36
     */
    public function create()
    {
        $this->validate($this->data, [
            'name'     => 'require',
            'remark'   => 'require',
            'rule_ids' => 'require',
            'sort'     => 'integer'
        ]);
        RoleService::create($this->data);
        return $this->renderSuccess();
    }

    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 17:46
     */
    public function update()
    {
        $this->validate($this->data, [
            'id'       => 'require',
            'name'     => 'require',
            'remark'   => 'require',
            'rule_ids' => 'require',
            'sort'     => 'integer'
        ]);
        RoleService::update($this->data);
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 18:08
     */
    public function delete()
    {
        RoleModel::select($this->data['ids'] ?? [])->delete();
        return $this->renderSuccess();
    }
}
