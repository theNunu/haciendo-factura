<?php

namespace App\Repositories;

use App\Models\Invoice;

class InvoiceRepository
{
    public function create(array $data): Invoice //cabecera de detalles
    {
        return Invoice::create($data);
    }


    public function getAll()
    {
        $invoices = Invoice::with('invoiceDetails')
            ->withSum('invoiceDetails as calculated_total', 'subtotal')
            ->get();

        return $invoices;

        // return Invoice::with('invoiceDetails')->get();
        // return  Invoice::all();
        // return "waza";
    }
}
