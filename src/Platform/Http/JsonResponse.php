<?php

namespace App\Platform\Http;

class JsonResponse extends Response
{
    public function __construct(array $content = [], int $responseCode = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';

        parent::__construct(json_encode($content), $responseCode, $headers);
    }
}