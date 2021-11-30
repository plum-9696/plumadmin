<?php

namespace app\model;

use plum\core\base\Model;
use think\model\concern\SoftDelete;

class RuleModel extends Model
{
    use SoftDelete;

    protected $name = 'rule';
    protected $type = ['menu_hidden' => 'boolean', 'keep_alive' => 'boolean'];
}
