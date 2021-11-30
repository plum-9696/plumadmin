<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RoleRule extends Migrator
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
        $this->table('role_rule', ['comment' => '角色规则中间表'])
            ->addColumn('role_id', 'integer', ['comment' => '角色ID', 'default' => 0])
            ->addColumn('rule_id', 'integer', ['comment' => '规则ID', 'default' => 0])
            ->save();
    }
}
