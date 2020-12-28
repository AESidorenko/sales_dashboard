<?php

namespace App\Platform\Database\ObjectMapper;

interface EntityInterface
{
    static function getTableName(): string;
}