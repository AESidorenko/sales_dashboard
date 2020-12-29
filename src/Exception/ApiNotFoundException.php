<?php

namespace App\Exception;

class ApiNotFoundException extends HttpProblemJsonException
{
    protected int $statusCode = 404;
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