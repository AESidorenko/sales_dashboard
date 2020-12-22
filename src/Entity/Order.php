<?php

namespace App\Entity;

use App\Platform\Database\ObjectMapper\EntityInterface;

class Order implements EntityInterface
{
    private int                $id;
    private \DateTimeImmutable $purchase_date;
    private string             $country;
    private string             $device;
    private int                $customer_id;

    static function getTableName(): string
    {
        return 'order';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getPurchaseDate(): \DateTimeImmutable
    {
        return $this->purchase_date;
    }

    /**
     * @param \DateTimeImmutable $purchase_date
     */
    public function setPurchaseDate(\DateTimeImmutable $purchase_date): void
    {
        $this->purchase_date = $purchase_date;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     */
    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     */
    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

}