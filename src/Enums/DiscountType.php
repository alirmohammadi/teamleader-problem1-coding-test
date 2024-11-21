<?php
namespace App\Enums;

enum DiscountType: string {
    case AMOUNT = 'amount';
    case FREE_PRODUCT = 'free_product';
}