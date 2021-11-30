<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\RuleModel;
use plum\helper\Arr;
use plum\helper\Helper;

class Rule extends AuthBackendController
{
    /**
     * 获取树状的规则列表
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:24
     */
    public function tree()
    {
        $rules = RuleModel::order(['sort', 'id'])
            ->select();
        $rules = Arr::tree($rules->toArray());
        return $this->renderSuccess($rules);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 14:18
     */
    public function create()
    {
        $this->validate($this->data, [
            'name'        => 'require',
            'parent_id'   => 'integer',
            'type'        => 'require|in:1,2,3',
            'method'      => 'requireIn:type,3',
            'routes'      => 'requireIn:type,2',
            'component'   => 'requireIn:type,2',
            'menu_hidden' => 'boolean',
            'keep_alive'  => 'boolean',
            'sort'        => 'integer'
        ]);
        RuleModel::create($this->data);
        return $this->renderSuccess();
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:13
     */
    public function update()
    {
        $this->validate($this->data, [
            'id'          => 'require',
            'name'        => 'require',
            'parent_id'   => 'integer',
            'type'        => 'require|in:1,2,3',
            'method'      => 'requireIn:type,3',
            'routes'      => 'requireIn:type,2',
            'component'   => 'requireIn:type,2',
            'menu_hidden' => 'boolean',
            'keep_alive'  => 'boolean',
            'sort'        => 'integer'
        ]);
        RuleModel::update($this->data);
        return $this->renderSuccess();
    }

    /**
     * 规则详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:18
     */
    public function detail()
    {
        if (!$detail = RuleModel::find($this->data['id'] ?? 0))
            error('规则不存在');
        return $this->renderSuccess($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:19
     */
    public function delete()
    {
        RuleModel::select($this->data['ids'] ?? [])->delete();
        return $this->renderSuccess();
    }
}
