<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{

    public function getAll(){
        return Product::all();
    }
    public function create(array $data)
    {
        // return Course::create($data);
        return Product::create([
            'name'         => $data['name'],
            'description'   => $data['description'],
            'stock'         => $data['stock'],
            'is_active'         => $data['is_active'],
            'price'    => $data['price'],
        ]);
    }
}
