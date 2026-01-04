<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $tables = "invoice_details";
    protected $primaryKey = "invoice_detail_id";
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }
    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id',   // FK en invoice_details
            'product_id'    // PK en products //no es necesario envviarle dos veces el id
        );
    }
}
