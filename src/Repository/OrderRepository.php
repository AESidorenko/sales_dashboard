<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Database\ObjectMapper\AbstractRepository;
use DateTimeImmutable;

class OrderRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        parent::__construct($connection, Order::class);
    }

    public function findTotalOrdersByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      o.purchase_date purchase_date, COUNT(o.id) orders_number
                FROM        `%s` o
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'
                GROUP BY	o.purchase_date
                ORDER BY    o.purchase_date";

        $params = [Order::getTableName()];

        $result = $this->query($sql, $params, false);

        $result->rewind();

        $resultArray = [];
        foreach ($result as $row) {
            $resultArray[] = [
                'x' => (new DateTimeImmutable($row->purchase_date))->getTimestamp() * 1000,
                'y' => (int)$row->orders_number
            ];
        }

        return $resultArray;
    }

    public function findRevenuesByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      o.purchase_date purchase_date, SUM(oi.quantity * oi.price) revenue
                FROM        `%s` o
                INNER JOIN  `%s` oi
                ON          oi.order_id = o.id
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'
                GROUP BY	o.purchase_date";

        $params = [Order::getTableName(), OrderItem::getTableName()];

        $result = $this->query($sql, $params, false);

        $resultArray = [];
        foreach ($result as $row) {
            $resultArray[] = [
                'x' => (new DateTimeImmutable($row->purchase_date))->getTimestamp() * 1000,
                'y' => (int)$row->revenue
            ];
        }

        return $resultArray;
    }

    public function getTotalRevenueByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate): float
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      SUM(oi.quantity * oi.price) total_revenue
                FROM        `%s` o
                INNER JOIN  `%s` oi
                ON          oi.order_id = o.id
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'";

        $params = [Order::getTableName(), OrderItem::getTableName()];

        $result = $this->query($sql, $params, false);

        return (float)$result->current()->total_revenue;
    }

    public function getTotalOrdersByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate): int
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      COUNT(o.id) total_orders
                FROM        `%s` o
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'";

        $params = [Order::getTableName()];

        $result = $this->query($sql, $params, false);

        return (int)$result->current()->total_orders;
    }
}