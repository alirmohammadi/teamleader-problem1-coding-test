
# Discount Microservice

## Setup

1. **Install dependencies:**
    ```sh
    composer install
    ```

2. **Run the service:** (You can use PHP's built-in server for simplicity)
    ```sh
       php -S localhost:8000 -t src/public src/public/router.php
    ```

## API Endpoints

- **POST `/calculate-discount`** - Calculates discounts for an order

## Example Payload

```json
{
   "id": "1",
   "customer-id": "1",
   "items": [
      {
         "product-id": "B102",
         "quantity": 10,
         "unit-price": 4.99,
         "total": 49.90
      }
   ],
   "total": 49.90
}
```

## Example Response

```json
{
   "order": {
      "orderId": 1,
      "customerId": 1,
      "products": [
         {
            "id": "B102",
            "name": "Press button",
            "category": 2,
            "price": 4.99,
            "quantity": 10,
            "discountedPrice": null
         }
      ],
      "total": 49.90
   },
   "discounts": [
      {
         "discount": {
            "product": {
               "id": "B102",
               "name": "Press button",
               "category": 2,
               "price": 4.99,
               "quantity": null,
               "discountedPrice": null
            }
         },
         "type": "free_product",
         "description": "Buy 5, get 1 free on Switches."
      }
   ],
   "afterDiscount": {
      "orderId": 1,
      "customerId": 1,
      "products": [
         {
            "id": "B102",
            "name": "Press button",
            "category": 2,
            "price": 4.99,
            "quantity": 11,
            "discountedPrice": null
         }
      ],
      "total": 49.90
   }
}
```

## Add New Discount Strategy

To add new discount strategies, follow these steps:

1. **Create a new class** in the `src/Discount` directory.
2. **Implement the `DiscountStrategyInterface` interface.**
3. **Define the `calculate` method** in your class. This method should return an array consisting of the `afterDiscount` order and the `DiscountDTO` object.

Here's an example of how your class might look:

```php
namespace Discount;

use DiscountStrategyInterface;
use Order;
use DiscountDTO;

class NewDiscountStrategy implements DiscountStrategyInterface
{
    public function calculate(Order $order): array
    {
        // Your discount logic here
        $afterDiscount = ...; // Modified $order after applying discounts
        $discountDTO = ...; // Your discount details

        return [$afterDiscount, $discountDTO];
    }
}
```

## Tests

Run tests using PHPUnit:
```sh
vendor/bin/phpunit test
```
