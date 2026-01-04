<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $tables = "invoices";
    protected $primaryKey = "invoice_id";
    protected $fillable = ['user_id', 'total', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'invoice_id');
    }

    public function invoiceDetails() //items
    {
        return $this->hasMany(InvoiceDetails::class, 'invoice_id');
    }
}
