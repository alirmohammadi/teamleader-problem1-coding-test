<?php
use PHPUnit\Framework\TestCase;
use App\Services\DiscountCalculator;

class DiscountCalculatorTest extends TestCase {
    public function testCalculateLoyaltyDiscountOrderSandwichDiscount(): void
    {
        $filePath = __DIR__ . '/../example-orders/order1.json';
        $order = file_get_contents($filePath);
        $filePath = __DIR__ . '/../example-orders/order1res.json';
        $expected = file_get_contents($filePath);

        $calculator = new DiscountCalculator();
        $discounts = $calculator->calculate($order);

        $this->assertJsonStringEqualsJsonString($expected,
            json_encode($discounts, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    public function testCalculateLoyaltyDiscountOrderSandwichAndLoyaltyDiscount(): void
    {
        $filePath = __DIR__ . '/../example-orders/order2.json';
        $order = file_get_contents($filePath);
        $filePath = __DIR__ . '/../example-orders/order2res.json';
        $expected = file_get_contents($filePath);

        $calculator = new DiscountCalculator();
        $discounts = $calculator->calculate($order);

       $this->assertJsonStringEqualsJsonString($expected,
           json_encode($discounts, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }
    public function testCalculateLoyaltyDiscountOrderToolDiscount(): void
    {
        $filePath = __DIR__ . '/../example-orders/order3.json';
        $order = file_get_contents($filePath);
        $filePath = __DIR__ . '/../example-orders/order3res.json';
        $expected = file_get_contents($filePath);

        $calculator = new DiscountCalculator();
        $discounts = $calculator->calculate($order);

        $this->assertJsonStringEqualsJsonString($expected,
            json_encode($discounts, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }
}