<?php

namespace app\controller;

use app\model\FileModel;
use app\model\UserModel;
use plum\core\base\AuthController;

class AuthBackendController extends AuthController
{

    protected function checkPermission()
    {

    }

    public function getUserinfo($id)
    {
        if (!$user = UserModel::find($id))
            error('用户不存在');
        return $user;
    }
}
