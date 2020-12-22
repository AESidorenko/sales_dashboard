<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Order;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Database\ObjectMapper\AbstractRepository;
use DateTimeImmutable;

class CustomerRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        parent::__construct($connection, Customer::class);
    }

    public function findCustomersNumberForPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      o.purchase_date purchase_date, COUNT(DISTINCT c.id) customers_number
                FROM        `%s` o
                INNER JOIN  `%s` c
                ON          o.customer_id = c.id
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'
                GROUP BY	o.purchase_date";

        $params = [Order::getTableName(), Customer::getTableName()];

        return array_map(fn($row) => [
            't' => (new DateTimeImmutable($row->purchase_date))->getTimestamp(),
            'y' => (int)$row->customers_number
        ], self::query($sql, $params, false));
    }
}