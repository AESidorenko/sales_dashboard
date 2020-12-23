<?php

namespace App\Controller\Api\v1;

use App\Exception\ApiBadRequestException;
use App\Exception\ApiForbiddenException;
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
        $this->validateAjax($request);

        $startDate = $request->query->get('startDate', '');
        $endDate   = $request->query->get('endDate', '');

        $this->validateDateRange($startDate, $endDate);

        $orders    = $orderRepository->findTotalOrdersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));
        $customers = $customerRepository->findTotalCustomersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));

        return new JsonResponse(['datasets' => [$customers, $orders], 'labels' => ['Customers', 'Orders']]);
    }

    public function revenuesAction(OrderRepository $orderRepository, CustomerRepository $customerRepository, Request $request): JsonResponse
    {
        $this->validateAjax($request);

        $startDate = $request->query->get('startDate', '');
        $endDate   = $request->query->get('endDate', '');

        $this->validateDateRange($startDate, $endDate);

        $orders   = $orderRepository->findTotalOrdersByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));
        $revenues = $orderRepository->findRevenuesByPeriod(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));

        return new JsonResponse(['datasets' => [$revenues, $orders], 'labels' => ['Revenues', 'Orders']]);
    }

    private function validateAjax(Request $request): void
    {
        if (!$request->isAjax()) {
            throw new ApiForbiddenException('Forbidden', 'Only XHTTP requests allowed');
        }
    }

    private function validateDateRange(string $startDate, string $endDate): void
    {
        if (!ParameterValidator::isValidDateString($startDate)) {
            throw new ApiBadRequestException('Invalid URL parameter', 'Required parameter is invalid or missing',
                [
                    [
                        ApiForbiddenException::FIELD_NAME   => 'startDate',
                        ApiForbiddenException::FIELD_REASON => 'must have date format: YYYY-MM-DD'
                    ]
                ]);
        }

        if (!ParameterValidator::isValidDateString($endDate)) {
            throw new ApiBadRequestException('Invalid URL parameter', 'Required parameter is invalid or missing',
                [
                    [
                        ApiForbiddenException::FIELD_NAME   => 'endDate',
                        ApiForbiddenException::FIELD_REASON => 'must have date format: YYYY-MM-DD'
                    ]
                ]);
        }

        if (DateTimeImmutable::createFromFormat('Y-m-d', $startDate) > DateTimeImmutable::createFromFormat('Y-m-d', $endDate)) {
            throw new ApiBadRequestException('Invalid URL parameters values', 'Start of period must be less or equal than the end',
                [
                    [
                        ApiForbiddenException::FIELD_NAME   => 'startDate',
                        ApiForbiddenException::FIELD_REASON => 'must be more or equal than endDate'
                    ],
                    [
                        ApiForbiddenException::FIELD_NAME   => 'endDate',
                        ApiForbiddenException::FIELD_REASON => 'must be less or equal than startDate'
                    ],
                ]);
        }
    }
}