<?php

namespace app\controller;

use app\model\FileModel;
use plum\core\base\AuthController;

class AuthBackendController extends AuthController
{

    protected function checkPermission()
    {
        // TODO: Implement checkPermission() method.
    }

    public function getUserinfo($id)
    {
        // TODO: Implement getUserinfo() method.
        return FileModel::find();
    }
}
