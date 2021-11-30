<?php

use think\migration\Migrator;
use think\migration\db\Column;

class File extends Migrator
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
        $this->table('file', ['comment' => "文件表"])
            ->addColumn('name', 'string', ['comment' => '文件名'])
            ->addColumn('path', 'string', ['comment' => '文件地址'])
            ->addColumn('url', 'string', ['comment' => '文件地址'])
            ->addColumn('driver', 'string', ['comment' => '上传类型'])
            ->addColumn('mime', 'string', ['comment' => 'MIME类型'])
            ->addColumn('size', 'integer', ['comment' => '文件大小(B)'])
            ->addColumn('module', 'string', ['comment' => '应用名称'])
            ->addColumn('uploader_id', 'integer', ['comment' => '上传者ID'])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
