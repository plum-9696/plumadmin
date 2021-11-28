<?php

namespace plum\core\exception;

use plum\core\enum\ResponseEnum;

class ForbiddenException extends Exception
{
    public $code = ResponseEnum::USER_FORBIDDEN;
    public $message = 'USER FORBIDDEN';
}
