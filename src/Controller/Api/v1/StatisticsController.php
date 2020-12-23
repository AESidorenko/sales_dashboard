<?php

namespace App\Controller\Api\v1;

use App\Helper\ParameterValidator;
use App\Platform\Http\JsonResponse;
use App\Platform\Http\Request;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use DateTimeImmutable;

class StatisticsController
{
    public function customersAction(OrderRepository $orderRepository, CustomerRepository $customerRepository, Request $request): JsonResponse
    {
        $startDate = $request->query->get('startDate', '');
        $endDate   = $request->query->get('endDate', '');

        if (!ParameterValidator::isValidDateString($startDate) || !ParameterValidator::isValidDateString($endDate)) {
            throw new \Exception('Bad request. Invalid date.', 400);
        }

        $orders    = $orderRepository->findTotalOrdersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));
        $customers = $customerRepository->findTotalCustomersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));

        return new JsonResponse(['datasets' => [$customers, $orders], 'labels' => ['Customers', 'Orders']]);
    }

    public function revenuesAction(OrderRepository $orderRepository, CustomerRepository $customerRepository, Request $request): JsonResponse
    {
        $startDate = $request->query->get('startDate', '');
        $endDate   = $request->query->get('endDate', '');

        if (!ParameterValidator::isValidDateString($startDate) || !ParameterValidator::isValidDateString($endDate)) {
            throw new \Exception('Bad request. Invalid date.', 400);
        }

        $orders   = $orderRepository->findTotalOrdersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));
        $revenues = $orderRepository->findRevenuesByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));

        return new JsonResponse(['datasets' => [$revenues, $orders], 'labels' => ['Revenues', 'Orders']]);
    }
}