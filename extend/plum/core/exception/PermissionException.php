<?php

namespace plum\core\exception;

use plum\core\enum\ResponseEnum;

class PermissionException extends Exception
{
    public $code = ResponseEnum::PERMISSION_FORBIDDEN;
    public $message = 'PERMISSION FORBIDDEN';
}
