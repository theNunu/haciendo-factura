<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {        
        $userId = $request->input('user_id');
        $items = $request->input('items');

        $invoice = $this->invoiceService->createInvoice($userId, $items);

        // estructura de respuesta clara
        return response()->json([
            'invoice_id' => $invoice->invoice_id,
            'user_id' => $invoice->user_id,
            'total' => $invoice->total,
            'items' => $invoice->invoiceDetails->map(fn($i) => [
                'product_id' => $i->product_id,
                'product_name' => $i->product->name ?? null,
                // 'nombre_producto' => $i->product->name ?? null,
                'unit_price' => $i->unit_price,
                'quantity' => $i->quantity,
                'subtotal' => $i->subtotal,
            ]),
            'created_at' => $invoice->created_at,
        ], 201);
    }
}
