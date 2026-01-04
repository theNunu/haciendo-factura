<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{

    public function getAll()
    {
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

    //________________________________________________
    public function findForUpdate(int $id)
    {
        // Para Postgres usamos lockForUpdate()
        return Product::where('product_id', $id)->lockForUpdate()->first();
    }

    public function find(int $id)
    {
        return Product::find($id);
    }

    public function decrementStock(Product $product, int $qty)
    {
        $product->stock = $product->stock - $qty;
        $product->save();
    }
    //________________________________________________
}
