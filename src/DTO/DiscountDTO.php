<?php

namespace App\DTO;

use App\Enums\DiscountType;

readonly class DiscountDTO
{

    public function __construct(
        public DiscountTypeDTO $discount,
        public DiscountType $type,
        public string $description
    ) {

    }


    public function getType(): DiscountType
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->type->value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \App\DTO\DiscountTypeDTO
     */
    public function getDiscount(): DiscountTypeDTO
    {
        return $this->discount;
    }

    public function jsonSerialize(): array
    {
        return [
            'discount' => $this->discount->jsonSerialize(),
            'type' => $this->type->value,
            'description' => $this->description,
        ];
    }
}