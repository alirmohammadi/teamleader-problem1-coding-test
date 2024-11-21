<?php

namespace App\Discounts;

use App\Domains\Order;
use App\Domains\Product;
use App\DTO\DiscountDTO;
use App\DTO\DiscountFreeProductDTO;
use App\Enums\DiscountType;

/**
 * Class SandwichDiscount
 *
 * This class provides methods to calculate and apply discounts for sandwich orders based on certain criteria.
 * Implements the DiscountStrategy interface.
 *
 * @package   App\Discounts
 */
class SandwichDiscount implements DiscountStrategyInterface
{
    /**
     * @var int $sandwichCategoryID
     * The category ID for sandwiches used to identify eligible products for discounts.
     */
    public int $sandwichCategoryID = 2;

    /**
     * @var int $limit
     * The limit for the number of sandwiches to be purchased to qualify for a discount.
     */
    public int $limit = 5;

    /**
     * Calculates and applies discounts to an order based on predefined criteria.
     *
     * @param Order $order The order containing products to be evaluated for discounts.
     *
     * @return array|null An array containing the updated order and calculated discounts, or null if no discounts are applicable.
     */
    public function calculate(Order $order): ?array
    {
        $discounts = [];

        foreach ($order->getProducts() as $key => $product) {
            if ($product->getCategory() === $this->sandwichCategoryID && $product->getNumber() >= $this->limit) {
                $discounts[] = new DiscountDTO(
                    new DiscountFreeProductDTO(Product::find($product->getId()), 1),
                    DiscountType::FREE_PRODUCT,
                    $this->getMessage()
                );
                $order->getProducts()[$key]->setNumber($product->getNumber() + 1);
            }
        }

        return [$order, $discounts];
    }

    /**
     * Retrieves the promotional message used in discounts.
     *
     * @return string The promotional message indicating the discount criteria.
     */
    public function getMessage(): string
    {
        return "Buy $this->limit, get 1 free on Switches.";
    }
}