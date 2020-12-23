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

    public function findTotalOrdersByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $dateSince = $startDate->format('Y-m-d');
        $dateTill  = $endDate->format('Y-m-d');

        $sql = "SELECT      o.purchase_date purchase_date, COUNT(o.id) orders_number
                FROM        `%s` o
                WHERE 		o.purchase_date BETWEEN '$dateSince' AND '$dateTill'
                GROUP BY	o.purchase_date";

        $params = [Order::getTableName()];

        return array_map(fn($row) => [
            'x' => (new DateTimeImmutable($row->purchase_date))->getTimestamp() * 1000,
            'y' => (int)$row->orders_number
        ], self::query($sql, $params, false));
    }

    public function findRevenuesByPeriod(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
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

        return array_map(fn($row) => [
            'x' => (new DateTimeImmutable($row->purchase_date))->getTimestamp() * 1000,
            'y' => (int)$row->revenue
        ], self::query($sql, $params, false));
    }
}