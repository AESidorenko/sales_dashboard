<?php

namespace App\Helper;

use InvalidArgumentException;

class StringNormalizerHelper
{
    public static function normalizeUrlComponents(array $urlComponents): array
    {
        array_walk($urlComponents, function (string &$componentString) {
            if (!preg_match('/^[a-zA-Z\-_]+$/', $componentString)) {
                throw new InvalidArgumentException(sprintf('Invalid signature: [%s]', $componentString), 400);
            }

            $componentString = str_replace('-', '_', $componentString);
            $elements        = array_filter(explode('_', $componentString));
            foreach ($elements as &$word) {
                $word = ucfirst(strtolower($word));
            }

            $componentString = implode($elements);
        });

        $urlComponents[1] = lcfirst($urlComponents[1]);

        return $urlComponents;
    }

    public static function toCamelCase(string $string): string
    {
        $parts = explode('_', $string);

        return lcfirst(implode(array_map('ucfirst', $parts)));
    }
}