<?php

namespace App\Services;

use App\Discounts\DiscountStrategyInterface;
use App\Domains\Order;
use App\Domains\Product;
use App\DTO\DiscountResponseServiceDTO;
use DirectoryIterator;
use ReflectionClass;

class DiscountCalculator
{
    const string DISCOUNT_NAMESPACE = 'App\\Discounts\\';

    /**
     * Processes an order JSON string, applies various discounts, and generates a response with the original and
     * discounted orders.
     *
     * @param  string  $orderJson  The JSON string representing the original order details.
     *
     * @return DiscountResponseServiceDTO The response data transfer object containing the original order, the applied
     *     discounts, and the order after discounts have been applied.
     * @throws \JsonException
     */
    public function calculate(string $orderJson): DiscountResponseServiceDTO
    {
        $order = $this->prepareOrder($orderJson);
        $discountedOrder = clone $order;
        $allDiscounts = [];
        $discountClasses = $this->retrieveDiscountClasses();

        foreach ($discountClasses as $discountClass) {
            $this->processDiscountClass($discountClass, $discountedOrder, $allDiscounts);
        }

        return $this->generateResponse($order, $allDiscounts, $discountedOrder);
    }

    /**
     * Prepares an order from a JSON string.
     *
     * @param  string  $orderJson  A JSON string representing the order details.
     *
     * @return Order The prepared order.
     */
    private function prepareOrder(string $orderJson): Order
    {
        $data = json_decode($orderJson, true, 512, JSON_THROW_ON_ERROR);
        $products = array_map(static function ($itemData) {
            $product = Product::find($itemData[ 'product-id' ]);
            if ($product === null) {
                throw new \RuntimeException('Product not found');
            }
            $product->setNumber($itemData[ 'quantity' ]);

            return $product;
        }, $data[ 'items' ]);

        return new Order(
            $data[ 'id' ],
            $data[ 'customer-id' ],
            $products,
            (float) $data[ 'total' ]
        );
    }

    /**
     * Retrieves an array of discount class names from the Discounts directory.
     * It scans the directory for PHP files and constructs the class names based on the file names.
     *
     * @return array An array containing the fully-qualified class names of discount classes.
     */
    private function retrieveDiscountClasses(): array
    {
        $classes = [];
        $directory = __DIR__.'/../Discounts';

        foreach (new DirectoryIterator($directory) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $className = self::DISCOUNT_NAMESPACE.pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $classes[] = $className;
            }
        }

        return $classes;
    }

    /**
     * Processes a given discount class and applies it to the order, updating the order and discounts array.
     *
     * @param  string  $discountClass  The fully qualified class name of the discount strategy.
     * @param  Order  &$order  The order object to which the discount will be applied. This parameter is passed by reference and will be modified directly.
     * @param  array  &$discounts  An array of discounts that have been applied. This parameter is passed by reference and will be updated with new discount results.
     *
     * @return void
     */
    private function processDiscountClass(string $discountClass, Order &$order, array &$discounts): void
    {
        if (class_exists($discountClass)) {
            $reflectionClass = new ReflectionClass($discountClass);
            if ($reflectionClass->implementsInterface(DiscountStrategyInterface::class)) {
                $discountInstance = $reflectionClass->newInstance();
                [$order, $discountResults] = $discountInstance->calculate($order) ?? [$order, []];
                $discounts = array_merge($discounts, $discountResults);
            }
        }
    }

    /**
     * Generates a response containing the original order, applied discounts, and the discounted order.
     *
     * @param  Order  $order  The original order before discounts are applied.
     * @param  array  $discounts  An array of discounts applied to the order.
     * @param  Order  $discountedOrder  The order after discounts have been applied.
     *
     * @return DiscountResponseServiceDTO The response data transfer object containing the order details and discounts.
     */
    private function generateResponse(
        Order $order,
        array $discounts,
        Order $discountedOrder
    ): DiscountResponseServiceDTO {
        return new DiscountResponseServiceDTO($order, $discounts, $discountedOrder);
    }
}