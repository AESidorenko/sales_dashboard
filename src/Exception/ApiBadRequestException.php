<?php

namespace App\Exception;

class ApiBadRequestException extends HttpProblemJsonException
{
    protected int $statusCode = 400;

    public function __construct(string $title, string $detail, array $invalidParams = [])
    {
        $rfcFields = [
            self::FIELD_TITLE          => $title,
            self::FIELD_DETAIL         => $detail,
            self::FIELD_INVALID_PARAMS => $invalidParams,
        ];

        parent::__construct($rfcFields);
    }
}