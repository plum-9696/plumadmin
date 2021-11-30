<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\FileModel;

class File extends AuthBackendController
{
    /**
     * 上传
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 18:00
     */
    public function upload()
    {
        $file = new FileModel();
        $info = $file->upload();
        return $this->renderSuccess($info);
    }
}
