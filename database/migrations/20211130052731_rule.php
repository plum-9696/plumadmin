<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Rule extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('rule', ['comment' => '规则表'])
            ->addColumn('name', 'string', ['comment' => '规则名称','default'=>''])
            ->addColumn('parent_id', 'integer', ['comment' => '上级规则','default'=>0])
            ->addColumn('type', 'integer', ['comment' => '类型(1-目录 2-菜单 3-权限)', 'limit' => 1,'default'=>1])
            ->addColumn('method', 'string', ['comment' => '后端调用的方法:控制器@方法名','default'=>''])
            ->addColumn('routes', 'string', ['comment' => '前端路由','default'=>''])
            ->addColumn('component', 'string', ['comment' => '组件地址','default'=>''])
            ->addColumn('icon', 'string', ['comment' => 'icon','default'=>''])
            ->addColumn('menu_hidden', 'integer', ['comment' => '菜单是否隐藏', 'limit' => 1,'default'=>0])
            ->addColumn('keep_alive', 'integer', ['comment' => '路由是否缓存', 'limit'=> 1,'default'=>1])
            ->addColumn('sort', 'integer', ['comment' => '排序(升序)','default'=>200])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
