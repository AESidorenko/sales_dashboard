<?php

namespace App\Repository;

use App\Entity\OrderItem;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Database\ObjectMapper\AbstractRepository;

class OrderItemRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        parent::__construct($connection, OrderItem::class);
    }
}