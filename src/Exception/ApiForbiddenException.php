<?php

namespace App\Exception;

class ApiForbiddenException extends HttpProblemJsonException
{
    protected int $statusCode = 403;
    private array $customRfcFields;

    public function __construct(string $title, string $detail)
    {
        $this->rfcFields = [
            self::FIELD_TITLE  => $title,
            self::FIELD_DETAIL => $detail,
        ];

        parent::__construct();
    }

    protected function getCustomRfcFields(): array
    {
        return $this->customRfcFields;
    }
}