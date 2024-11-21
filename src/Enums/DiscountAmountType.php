<?php
namespace App\Enums;

enum DiscountAmountType: string {
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';
}