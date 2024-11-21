<?php

namespace App\Domains;

class Customer
{
    public function __construct(public int $id,public string $name,public float $totalSpent)
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTotalSpent(): float
    {
        return $this->totalSpent;
    }

    /**
     * @throws \JsonException
     */
    public static function getCustomers(): array
    {
        $filePath = __DIR__ . '/../../data/customers.json';
        $customersData = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

        $customers = [];
        foreach ($customersData as $customerData) {
            $customers[] = new self(
                (int)$customerData['id'],
                $customerData['name'],
                (float)$customerData['revenue']
            );
        }

        return $customers;
    }

    public static function find(int $id): ?self
    {
        try {
            $customers = self::getCustomers();
        }
        catch (\JsonException $e) {
            return null;
        }

        return array_find($customers, static fn($customer) => $customer->getId() === $id);
    }
}