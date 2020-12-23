<?php

namespace App\Repository;

use App\Entity\Order;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Database\ObjectMapper\AbstractRepository;

class OrderRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        parent::__construct($connection, Order::class);
    }
}