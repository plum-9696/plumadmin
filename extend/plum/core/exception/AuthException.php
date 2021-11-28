<?php

namespace plum\core\exception;

use plum\core\enum\ResponseEnum;

class AuthException extends Exception
{
    public $code = ResponseEnum::LOST_LOGIN;
    public $message = 'NOT LOGGED IN';
}
