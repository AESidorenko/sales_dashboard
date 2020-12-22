<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Platform\Database\DatabaseConnectionInterface;
use App\Platform\Database\ObjectMapper\AbstractRepository;

class CustomerRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $connection)
    {
        parent::__construct($connection, Customer::class);
    }
}