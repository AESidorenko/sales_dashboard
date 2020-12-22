<?php

namespace App\Controller\Api\v1;

use App\Platform\Http\Response;
use App\Repository\CustomerRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;

class StatisticsController
{
    public function ordersAction(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();

        return new Response(json_encode(['title' => 'orders']));
    }

    public function revenuesAction(OrderItemRepository $orderItemRepository): Response
    {
        $orderitems = $orderItemRepository->findAll();

        return new Response(json_encode(['title' => 'revenues']));
    }

    public function customersAction(CustomerRepository $customerRepository): Response
    {
        $customers = $customerRepository->findAll();

        return new Response(json_encode(['title' => 'customers']));
    }
}