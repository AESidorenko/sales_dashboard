<?php

namespace App\Exception;

class ApiBadRequestException extends HttpProblemJsonException
{
    protected int $statusCode = 400;
    private array $customRfcFields;

    public function __construct(string $title, string $detail, array $invalidParams = [])
    {
        $this->customRfcFields = [
            self::FIELD_TITLE          => $title,
            self::FIELD_DETAIL         => $detail,
            self::FIELD_INVALID_PARAMS => $invalidParams,
        ];

        parent::__construct();
    }

    public function getCustomRfcFields(): array
    {
        return $this->customRfcFields;
    }
}