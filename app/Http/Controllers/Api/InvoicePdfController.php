<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function show($invoiceId)
    {
        // 1️⃣ Obtener factura con relaciones
        $invoice = Invoice::with('invoiceDetails.product')
            ->findOrFail($invoiceId);

        // 2️⃣ Generar PDF
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice
        ]);

        // 3️⃣ Mostrar en navegador o descargar
        return $pdf->stream("invoice_{$invoice->invoice_id}.pdf");
        // return $pdf->download("invoice_{$invoice->invoice_id}.pdf");
    }
}
