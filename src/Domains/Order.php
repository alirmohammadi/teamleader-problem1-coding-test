<?php
namespace App\Domains;

class Order {
    public function __construct(public int $orderId,public int $customerId,public array $products,public float $total) {}

    public function getOrderId(): int {
        return $this->orderId;
    }

    public function getCustomerId(): int {
        return $this->customerId;
    }

    public function getProducts(): array {
        return $this->products;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }



    public function jsonSerialize(): array {
        return [
            'id' => $this->orderId,
            'customer_id' => $this->customerId,
            'items' => array_map(static function(Product $item) {
                return $item->jsonSerialize();
            }, $this->products),
            'total' => $this->total,
        ];
    }

    public function __clone() {
        if (!empty($this->products)) {
            foreach ($this->products as $key => $product) {
                $this->products[$key] = clone $product;
            }
        }
    }
}