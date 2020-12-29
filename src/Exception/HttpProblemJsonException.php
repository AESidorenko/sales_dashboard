<?php

namespace App\Exception;

// Following to https://tools.ietf.org/html/rfc7807

abstract class HttpProblemJsonException extends \Exception
{
    public const    FIELD_TITLE          = 'title';
    protected const FIELD_DETAIL         = 'detail';
    protected const FIELD_INVALID_PARAMS = 'invalid-params';
    public const    FIELD_NAME           = 'name';
    public const    FIELD_REASON         = 'reason';

    protected int   $statusCode = 500;
    protected array $rfcFields  = [self::FIELD_TITLE => 'Internal server error'];

    public function __construct()
    {
        $rfcFields = array_merge($this->rfcFields, $this->getCustomRfcFields());

        parent::__construct(json_encode($rfcFields), $this->statusCode);
    }

    abstract protected function getCustomRfcFields(): array;
}