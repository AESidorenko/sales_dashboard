<?php

namespace App\Helper;

class ParameterValidator
{
    public static function isValidDateString(string $dateString)
    {
        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dateString) !== 1) {
            return false;
        };

        [$year, $month, $day] = explode('-', $dateString);

        return checkdate($month, $day, $year);
    }
}