<?php

namespace App\Services;

use App\Models\InvoiceDetails;
use App\Repositories\ProductRepository;
use App\Repositories\InvoiceRepository;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    protected $productRepo;
    protected $invoiceRepo;

    public function __construct(ProductRepository $productRepo, InvoiceRepository $invoiceRepo)
    {
        $this->productRepo = $productRepo;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * $userId: id del usuario que factura
     * $items: array de { product_id, quantity }
     */
    public function createInvoice(int $userId, array $items)
    {
        return DB::transaction(function () use ($userId, $items) {
            // 1) Crear cabecera con total temporal 0
            $invoice = $this->invoiceRepo->create([
                'user_id' => $userId,
                'total' => 0
            ]);

            $total = 0;
            $details = [];

            // 2) Por cada item: lock product, verificar stock, crear detalle, descontar stock
            foreach ($items as $item) {
                $product = $this->productRepo->findForUpdate($item['product_id']);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'product_id' => ["Producto con ID {$item['product_id']} no encontrado."]
                    ]);
                }

                $qty = (int) $item['quantity'];

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => ["Stock insuficiente para el producto {$product->product_id} ({$product->name}). Disponible: {$product->stock}, solicitado: {$qty}"]
                    ]);
                }

                $unitPrice = $product->price;
                $subtotal = round($unitPrice * $qty, 2);

                // crear detalle
                $detail = InvoiceDetails::create([
                    'invoice_id' => $invoice->invoice_id,
                    'product_id' => $product->product_id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // descontar stock
                $this->productRepo->decrementStock($product, $qty);

                $total += $subtotal;
                $details[] = $detail;
            }

            // 3) actualizar total en cabecera
            $invoice->total = round($total, 2);
            $invoice->save();

            // 4) cargar relación items para retornar
            // $invoice->load('items.product');
            $invoice->load('invoiceDetails.product');

            return $invoice;
        });
    }
}
