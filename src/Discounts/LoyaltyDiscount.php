<?php

namespace App\Discounts;

use App\Domains\Order;
use App\Domains\Customer;
use App\DTO\DiscountAmountTypeDTO;
use App\DTO\DiscountDTO;
use App\Enums\DiscountAmountType;
use App\Enums\DiscountType;

/**
 * Class LoyaltyDiscount
 *
 * This class provides functionality to calculate loyalty discounts for customers.
 *
 * @package App\Discounts
 */
class LoyaltyDiscount implements DiscountStrategyInterface
{
    /**
     * @var DiscountType The type of discount being applied.
     */
    public DiscountType $type = DiscountType::AMOUNT;

    /**
     * @var float The amount of discount to apply.
     */
    public float $amount = 10;

    /**
     * @var float The spending limit to be eligible for the loyalty discount.
     */
    public float $limit = 1000;

    /**
     * Calculates discount for a given order based on customer's eligibility.
     *
     * @param  Order  $order  The order for which the discount is calculated.
     *
     * @return DiscountDTO[]|null The calculated discount details, or null if the customer is not eligible.
     */
    public function calculate(Order $order): ?array
    {
        $customer = $this->getCustomerById($order->getCustomerId());
        if (($customer instanceof Customer) && $this->isEligibleForLoyaltyDiscount($customer)) {
            $discount = [new DiscountDTO(new DiscountAmountTypeDTO($this->amount, DiscountAmountType::PERCENTAGE), $this->type, $this->getMessage())];
            $order->setTotal($this->getAmount($order));

            return [$order, $discount];
        }

        return [$order, []];
    }

    /**
     * Retrieves a customer by their unique identifier.
     *
     * @param  int  $id  The unique identifier of the customer.
     *
     * @return Customer|null The customer object if found, or null if not found.
     */
    public function getCustomerById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Determines if the given customer is eligible for a loyalty discount based on their total spent amount.
     *
     * @param  Customer  $customer  The customer object to check eligibility for.
     *
     * @return bool True if the customer is eligible for a loyalty discount, false otherwise.
     */
    public function isEligibleForLoyaltyDiscount(Customer $customer): bool
    {
        return $customer->getTotalSpent() > $this->limit;
    }

    /**
     * Retrieves the message indicating eligibility for a loyalty discount based on spending.
     *
     * @return string The message detailing the loyalty discount eligibility.
     */
    public function getMessage(): string
    {
        return "Loyalty discount for spending over €" . $this->limit;
    }

    /**
     * Calculates the discounted total for the order.
     *
     * @param  Order  $order  The order for which the total is recalculated.
     *
     * @return float|int The new total after applying the discount.
     */
    public function getAmount(Order $order): int|float
    {
        return $order->getTotal() * (100 - $this->amount) / 100;
    }
}