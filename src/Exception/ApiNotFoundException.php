<?php

namespace App\Exception;

class ApiNotFoundException extends HttpProblemJsonException
{
    protected int $statusCode = 404;

    public function __construct(string $title, string $detail)
    {
        $rfcFields = [
            self::FIELD_TITLE  => $title,
            self::FIELD_DETAIL => $detail,
        ];

        parent::__construct($rfcFields);
    }
}