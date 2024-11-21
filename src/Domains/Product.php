<?php

namespace App\Domains;

class Product
{

    public function __construct(
        public string $id,
        public string $name,
        public int $category,
        public float $price,
        public ?int $number = null,
        public ?float $discountedPrice = null
    ) {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @throws \JsonException
     */
    public static function getProducts(): array
    {
        $filePath = __DIR__.'/../../data/products.json';
        $productsData = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

        $products = [];
        foreach ($productsData as $productData) {
            $products[] = new self(
                $productData[ 'id' ],
                $productData[ 'description' ],
                (int) $productData[ 'category' ],
                (float) $productData[ 'price' ]
            );
        }

        return $products;
    }

    public static function find(string $id): ?self
    {
        $products = self::getProducts();

        return array_find($products, static fn($product) => $product->getId() === $id);
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getDiscountedPrice(): ?float
    {
        return $this->discountedPrice;
    }

    public function setDiscountedPrice(?float $discountedPrice): void
    {
        $this->discountedPrice = $discountedPrice;
    }


    public function jsonSerialize(): array
    {
        $data = [
            'id'          => $this->id,
            'description' => $this->name,
            'category'    => $this->category,
            'price'       => $this->price,
        ];

        if ($this->number !== null) {
            $data['number'] = $this->number;
        }

        if (!empty($this->discountedPrice)) {
            $data['discountedPrice'] = $this->discountedPrice ??  $this->price;
        }

        return $data;
    }


}