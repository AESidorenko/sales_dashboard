<?php

namespace App\Exception;

class ApiForbiddenException extends HttpProblemJsonException
{
    protected int $statusCode = 403;

    public function __construct(string $title, string $detail)
    {
        $rfcFields = [
            self::FIELD_TITLE  => $title,
            self::FIELD_DETAIL => $detail,
        ];

        parent::__construct($rfcFields);
    }
}