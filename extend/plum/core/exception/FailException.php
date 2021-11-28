<?php

namespace plum\core\exception;

use plum\core\enum\ResponseEnum;

class FailException extends Exception
{
    public $code = ResponseEnum::FAILED;
    public $message = 'ERROR';
}
