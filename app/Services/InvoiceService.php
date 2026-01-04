<?php

namespace App\Services;

use App\Models\InvoiceDetails;
use App\Repositories\ProductRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

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

            // 1) Crear cabecera
            $invoice = $this->invoiceRepo->create([
                'user_id' => $userId,
                'total' => 0
            ]);

            // 2) Agrupar items por producto
            $groupedItems = [];

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $qty = (int) $item['quantity'];

                if (!isset($groupedItems[$productId])) {
                    $groupedItems[$productId] = 0;
                }

                $groupedItems[$productId] += $qty;
            }

            // 3) Validar stock TOTAL por producto
            foreach ($groupedItems as $productId => $totalQty) {
                $product = $this->productRepo->findForUpdate($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'product_id' => ["Producto con ID {$productId} no encontrado."]
                    ]);
                }

                if ($product->stock < $totalQty) {
                    throw new HttpResponseException(
                        response()->json([
                            'success' => false,
                            'message' => 'Stock insuficiente',
                            'errors' => [
                                'stock' => [
                                    "Stock insuficiente para el producto {$product->name}. Disponible: {$product->stock}, solicitado: {$totalQty}"
                                ]
                            ]
                        ], 422)
                    );
                    // throw ValidationException::withMessages([
                    //     'stock' => [
                    //         "Stock insuficiente para el producto {$product->product_id} ({$product->name}). " .
                    //             "Disponible: {$product->stock}, solicitado: {$totalQty}"
                    //     ]
                    // ]);
                }
            }

            // 4) Crear detalles y calcular total
            $total = 0;

            foreach ($items as $item) {
                $product = $this->productRepo->findForUpdate($item['product_id']);

                $qty = (int) $item['quantity'];
                $unitPrice = $product->price;
                $subtotal = round($unitPrice * $qty, 2);

                InvoiceDetails::create([
                    'invoice_id' => $invoice->invoice_id,
                    'product_id' => $product->product_id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            // 5) Descontar stock (una vez por producto)
            foreach ($groupedItems as $productId => $totalQty) {
                $product = $this->productRepo->findForUpdate($productId);
                $this->productRepo->decrementStock($product, $totalQty);
            }

            // 6) Actualizar total
            $invoice->total = round($total, 2);
            $invoice->save();

            // 7) Cargar relaciones
            $invoice->load('invoiceDetails.product');

            return $invoice;
        });
    }
}
