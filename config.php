<?php

return [
    'dbConnectionParameters' => [
        'dbHost'     => 'localhost',
        'dbUsername' => 'root',
        'dbPassword' => 'password',
        'dbSchema'   => 'sales_dashboard',
        'dbType'     => 'mysqli',
    ],

    /**
     * Explicit routes configuration (for the routes whose URI doesn't match PSR-4 classname path)
     */
    'routes'                 => [
        '/api/v1/statistics/orders'    => 'App\Controller\Api\v1\StatisticsController::ordersAction',
        '/api/v1/statistics/revenues'  => 'App\Controller\Api\v1\StatisticsController::revenuesAction',
        '/api/v1/statistics/customers' => 'App\Controller\Api\v1\StatisticsController::customersAction',
        '/api/v1/statistics/summary'   => 'App\Controller\Api\v1\StatisticsController::summaryAction',
    ],
];
