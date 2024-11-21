<?php

namespace App\DTO;

use App\Domains\Product;

class DiscountFreeProductDTO extends DiscountTypeDTO
{

    public function __construct(public Product $product)
    {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product->jsonSerialize(),
        ];
    }
}