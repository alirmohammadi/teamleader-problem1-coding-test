<?php
namespace App\Discounts;

use App\Domains\Order;

/**
 * Interface DiscountStrategy
 *
 * Provides a method to calculate a discount based on the given order.
 */
interface DiscountStrategyInterface {

    /**
     * Calculates the necessary values based on the given order.
     *
     * @param  Order  $order  The order object containing all required data for the calculation.
     *
     * @return array|null An associative array with the calculated values or null if the calculation fails.
     */
    public function calculate(Order $order): ?array;
}