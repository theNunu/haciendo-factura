<?php

namespace App\DTOs;

use App\Models\Product;

class ProductDTO
{
    public function __construct(
        public int $product_id,
        public string $name,
        public string $description,
        public int $stock,
        public bool $is_active,
        public string $price
    ) {}

    /**
     * Convierte un Model Product en un DTO
     */
    public static function fromModel(Product $product): self
    {
        return new self(
            product_id: $product->product_id,
            name: $product->name,
            description: $product->description,
            stock: $product->stock,
            is_active: $product->is_active,
            price: '$' . number_format($product->price, 2)
        );
    }
}
