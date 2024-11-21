<?php

namespace App\DTO;

use App\Domains\Product;
use App\Enums\DiscountAmountType;

class DiscountAmountTypeDTO extends DiscountTypeDTO
{

    public function __construct(public float $amount,public DiscountAmountType $type,public ?Product $product = null)
    {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getType(): DiscountAmountType
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->type->value;
    }

    public function getProductId(): ?Product
    {
        return $this->product;
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'type' => $this->type->value,
            'product' =>$this->product?->jsonSerialize(),
        ];
    }


}