<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepostirory;
    /**
     * Create a new class instance.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepostirory = $productRepository;
    }

    public function index()
    {
        $products =  $this->productRepostirory->getAll();
        // return $products;
        return $products->map(function ($product) {

            return [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'description' => $product->description,
                'stock' => $product->stock,
                'is_active' => $product->is_active,
                'price' => "$".$product->price
            ];
        });
    }

    public function create($data)
    {
        $product = $this->productRepostirory->create($data);
        $mensaje = "$" . $data['price'];

        return [
            // $product
            "product_id" => $product->product_id,
            "name" => $data['name'],
            "description" => $data['description'],
            "stock" => $data['stock'],
            "is_active" => $data['is_active'],
            // "product" => $product,
            "price" => $mensaje
        ];
    }
}
