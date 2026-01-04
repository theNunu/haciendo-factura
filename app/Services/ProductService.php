<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return $this->productRepository->getAll()->map(fn($product) => ProductDTO::fromModel($product));
        // $products =  $this->productRepostirory->getAll();
        // // return $products;
        // return $products->map(function ($product) {

        //     return [
        //         'product_id' => $product->product_id,
        //         'name' => $product->name,
        //         'description' => $product->description,
        //         'stock' => $product->stock,
        //         'is_active' => $product->is_active,
        //         'price' => "$".$product->price
        //     ];
        // });
    }


    public function find(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function create($data)
    {

        $product = $this->productRepository->create($data);

        return ProductDTO::fromModel($product);
        // $product = $this->productRepository->create($data);
        // $mensaje = "$" . $data['price'];

        // return [
        //     // $product
        //     "product_id" => $product->product_id,
        //     "name" => $data['name'],
        //     "description" => $data['description'],
        //     "stock" => $data['stock'],
        //     "is_active" => $data['is_active'],
        //     // "product" => $product,
        //     "price" => $mensaje
        // ];
    }
}
