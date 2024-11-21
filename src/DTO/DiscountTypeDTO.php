<?php

namespace App\DTO;

abstract class DiscountTypeDTO
{
   abstract public function jsonSerialize(): array;
}