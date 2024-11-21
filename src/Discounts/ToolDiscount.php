<?php

namespace App\Discounts;

use App\Domains\Order;
use App\Domains\Product;
use App\DTO\DiscountAmountTypeDTO;
use App\DTO\DiscountDTO;
use App\Enums\DiscountAmountType;
use App\Enums\DiscountType;

/**
 * Class ToolDiscount
 *
 * A discount strategy for applying discounts on tool products within an order.
 *
 * @package App\Discounts
 */
class ToolDiscount implements DiscountStrategyInterface
{
    /**
     * @var int The category ID of tools that this discount strategy is applied to.
     */
    public int $toolCategoryID = 1;

    /**
     * @var int The minimum quantity of tools required in the order to qualify for the discount.
     */
    public int $limit = 2;

    /**
     * @var int The discount percentage to be applied to the cheapest tool if the order qualifies for the discount.
     */
    public int $amount = 20;

    /**
     * Calculates the discount for an order based on the tool products it contains.
     * If the order has at least the minimum number of tools specified by $limit,
     * a discount is applied to the cheapest tool.
     *
     * @param Order $order The order object containing the list of products.
     * @return array|null Returns an array containing the updated Order object and the list of DiscountDTO objects if a discount is applied.
     *                    Returns null if no discounts are applicable.
     */
    public function calculate(Order $order): ?array
    {
        $discounts = [];
        /**
         * @var Product[] $tools
         */
        $tools = [];

        foreach ($order->getProducts() as $product) {
            if ($product->getCategory() === $this->toolCategoryID) {
                $tools[] = $product;
            }
        }

        if (count($tools) >= $this->limit) {
            usort($tools, static fn($a, $b) => $a->getPrice() <=> $b->getPrice());
            $tools[0]->setDiscountedPrice($tools[0]->getPrice() - $this->getAmount($tools[0]));
            $discounts[] = new DiscountDTO(
                new DiscountAmountTypeDTO(
                    $this->getTotalAmount($tools[0]), DiscountAmountType::FIXED, $tools[0]),
                DiscountType::AMOUNT,
                $this->getMessage());
            $order->setTotal($order->getTotal() - $this->getTotalAmount($tools[0]));
            foreach ($order->getProducts() as $key => $product) {
                if ($product->getId() === $tools[0]->getId()) {
                    $order->getProducts()[$key]->setDiscountedPrice($product->getPrice() - $this->getAmount($tools[0]));
                }
            }
        }

        return [$order, $discounts];
    }

    /**
     * Generates a message indicating the discount applied.
     *
     * @return string Returns a string message indicating the discount details.
     */
    public function getMessage(): string
    {
        return "$this->amount% off on the cheapest Tool.";
    }

    /**
     * Calculates the discount amount based on the price and quantity of a specific tool product.
     *
     * @param Product $tool The tool product for which the discount amount is to be calculated.
     * @return float|int Returns the calculated discount amount.
     */
    public function getTotalAmount(Product $tool): int|float
    {
        return $this->getAmount($tool) * $tool->getNumber();
    }

    /**
     * Calculates the discount amount based on the price and quantity of a specific tool product.
     *
     * @param Product $tool The tool product for which the discount amount is to be calculated.
     * @return float|int Returns the calculated discount amount.
     */
    public function getAmount(Product $tool): int|float
    {
        return $tool->getPrice()  * $this->amount / 100;
    }
}