<?php

namespace App\DTO;

use App\Domains\Order;

class DiscountResponseServiceDTO {
    public function __construct(public Order $order,public array $discounts,public Order $afterDiscount) {}

    public function getOrder(): Order {
        return $this->order;
    }

    public function getDiscounts(): array {
        return $this->discounts;
    }

    public function jsonSerialize(): array {
        return [
            'order' => $this->order->jsonSerialize(),
            'discounts' => array_map(static function($discount) {
                return $discount->jsonSerialize();
            }, $this->discounts),
            'afterDiscount' => $this->afterDiscount->jsonSerialize(),
        ];
    }

    /**
     * @return \App\Domains\Order
     */
    public function getAfterDiscount(): Order
    {
        return $this->afterDiscount;
    }
}