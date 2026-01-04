<?php

namespace App\Repositories;

use App\Models\Invoice;

class InvoiceRepository
{
    public function create(array $data): Invoice //cabecera de detalles
    {
        return Invoice::create($data);
    }
}
