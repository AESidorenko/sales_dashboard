<?php

namespace App\Controller\Api\v1;

use App\Helper\ParameterValidator;
use App\Platform\Http\JsonResponse;
use App\Platform\Http\Request;
use App\Platform\Http\Response;
use App\Repository\CustomerRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use DateTimeImmutable;

class StatisticsController
{
    public function ordersAction(OrderRepository $orderRepository, Request $request): Response
    {
        $orders = $orderRepository->findOrdersNumberByPeriod();

        return new Response(json_encode(['title' => 'orders']));
    }

    public function revenuesAction(OrderItemRepository $orderItemRepository): Response
    {
        $orderitems = $orderItemRepository->findAll();

        return new Response(json_encode(['title' => 'revenues']));
    }

    public function customersAction(CustomerRepository $customerRepository, Request $request): JsonResponse
    {
        $startDate = $request->query->get('startDate', '');
        $endDate   = $request->query->get('endDate', '');

        if (!ParameterValidator::isValidDateString($startDate) || !ParameterValidator::isValidDateString($endDate)) {
            throw new \Exception('Bad request. Invalid date.', 400);
        }

        $customersByDays = $customerRepository->findCustomersNumberForPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));

        return new JsonResponse(['data' => json_decode(json_encode($customersByDays, true))]);
    }
}