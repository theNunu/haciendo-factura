<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $tables = "products";
    protected $primaryKey = "product_id";

    protected $fillable = [
        'name',
        'description',
        'stock',
        'is_active',
        'price'
    ];

    
}
