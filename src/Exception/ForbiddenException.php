<?php

namespace App\Exception;

class ForbiddenException extends \Exception
{
    protected int $statusCode = 403;

    public function __construct($message = '')
    {
        parent::__construct($message, $this->statusCode);
    }
}