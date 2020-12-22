<?php

namespace App\Controller\Api\v1;

use App\Platform\Http\Response;

class StatisticsController
{
    public function ordersAction(): Response
    {
        return new Response(json_encode(['title' => 'orders']));
    }

    public function revenuesAction(): Response
    {
        return new Response(json_encode(['title' => 'revenues']));
    }

    public function customersAction(): Response
    {
        return new Response(json_encode(['title' => 'customers']));
    }
}