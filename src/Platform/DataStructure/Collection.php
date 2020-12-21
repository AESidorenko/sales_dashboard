<?php
declare(strict_types=1);

namespace App\Platform\DataStructure;

class Collection
{
    private array $collection = [];

    /**
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        $this->collection = $collection;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->collection);
    }

    public function get(string $key, string $default = null): string
    {
        return $this->has($key) ? $this->collection[$key] : $default;
    }

    public function all(): array
    {
        return $this->collection;
    }
}