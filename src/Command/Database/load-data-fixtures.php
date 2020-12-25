<?php

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Platform\Database\DatabaseConnectionFactory;
use App\Platform\Database\DatabaseConnectionInterface;

include_once __DIR__ . '/../bootstrap.php';

try {
    $db = DatabaseConnectionFactory::createDatabaseConnection($configManager->get('dbConnectionParameters'));

    $customers = createRandomCustomers(10, $db);
    createRandomOrders(new DateTime('2020-11-15'), new DateTime('2020-12-31'), $customers, $db);

} catch (Exception $exception) {
    printf("Data fixtures loading failed. Error message: %s" . PHP_EOL, $exception->getMessage());
    exit(1);
}

function createRandomCustomers(int $customersNumber, DatabaseConnectionInterface $db): array
{
    /** @var Customer[] $customers */
    $customers = array_fill(0, $customersNumber, null);

    foreach ($customers as &$customer) {
        $customer = new Customer;
        $customer->setEmail(generateRandomString(5) . '@example.com');
        $customer->setFirstname(ucfirst(generateRandomString(6)));
        $customer->setLastname(ucfirst(generateRandomString(8)));

        $db->query('INSERT INTO customer (email, firstname, lastname) VALUES("%s", "%s", "%s")', [
            $customer->getEmail(),
            $customer->getFirstname(),
            $customer->getLastname()
        ]);

        $customer->setId($db->getLastInsertId());
    }

    return $customers;
}

function createRandomOrders(DateTime $startDate, DateTime $endDate, array $customers, $db)
{
    $customersIds = array_map(fn($c) => $c->getId(), $customers);

    $date = $startDate;

    /** @var Order[] $orders */
    $orders = [];

    while ($date <= $endDate) {
        // let 30% of days have no orders
        if (rand(1, 10) > 3) {
            $ordersOfDay = array_fill(0, rand(3, 10), null);
            foreach ($ordersOfDay as &$order) {
                $order = new Order();
                $order->setPurchaseDate(DateTimeImmutable::createFromMutable($date));
                $order->setCountry(generateRandomString(2));
                $order->setDevice(generateRandomString(10));
                $order->setCustomerId($customersIds[array_rand($customersIds, 1)]);

                $db->query('INSERT INTO `order` (purchase_date, country, device, customer_id) VALUES("%s", "%s", "%s", "%s")', [
                    $order->getPurchaseDate()->format('Y-m-d'),
                    $order->getCountry(),
                    $order->getDevice(),
                    $order->getCustomerId()
                ]);

                $order->setId($db->getLastInsertId());
            }

            $orders = array_merge($orders, $ordersOfDay);
        }

        $date->modify('+1 day');
    }

    $orderItems = [];
    foreach ($orders as &$order) {
        /** @var OrderItem[] $items */
        $items = array_fill(0, rand(1, 5), null);
        foreach ($items as &$item) {
            $item = new OrderItem();
            $item->setPrice(rand(0, 100) / 100 * 50);
            $item->setEan(generateRandomNumberString(13));
            $item->setOrderId($order->getId());
            $item->setQuantity(rand(1, 5));

            $db->query('INSERT INTO order_item (price, ean, order_id, quantity) VALUES("%s", "%s", "%s", "%s")', [
                $item->getPrice(),
                $item->getEan(),
                $item->getOrderId(),
                $item->getQuantity()
            ]);

            $item->setId($db->getLastInsertId());
        }

        $orderItems = array_merge($orderItems, $items);
    }
}

function generateRandomString(int $length): string
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

function generateRandomNumberString(int $length): string
{
    $randomString     = substr(str_shuffle('0123456789'), 0, $length - 1);
    $randomFirstDigit = substr(str_shuffle('123456789'), 0, 1);

    return $randomFirstDigit . $randomString;
}