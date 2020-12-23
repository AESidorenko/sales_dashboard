<?php

namespace App\Exception;

// Following to https://tools.ietf.org/html/rfc7807

abstract class HttpProblemJsonException extends \Exception
{
    protected const FIELD_TITLE          = 'title';
    protected const FIELD_DETAIL         = 'detail';
    protected const FIELD_INVALID_PARAMS = 'invalid-params';
    protected const FIELD_NAME           = 'name';
    protected const FIELD_REASON         = 'reason';

    protected int   $statusCode = 500;
    protected array $rfcFields  = [self::FIELD_TITLE => 'Internal server error'];

    public function __construct(array $customRfcFields)
    {
        $this->rfcFields = array_merge($this->rfcFields, $customRfcFields);

        parent::__construct(json_encode($customRfcFields), $this->statusCode);
    }

    public function getRfcFields(): array
    {
        return $this->rfcFields;
    }
}