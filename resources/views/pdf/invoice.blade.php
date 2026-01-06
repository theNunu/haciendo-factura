<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $invoice->invoice_id }}</title>

    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }

        /* Encabezado */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #444;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .info-text {
            text-align: right;
            line-height: 1.5;
        }

        /* Información del Cliente */
        .client-section {
            margin-bottom: 30px;
        }

        /* Tabla de Productos */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.items-table th {
            background-color: #f2f2f2;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
        }

        table.items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        /* Total */
        .total-container {
            margin-top: 20px;
            text-align: right;
        }

        .total-box {
            display: inline-block;
            background-color: #f9f9f9;
            padding: 10px 20px;
            border: 1px solid #ccc;
        }

        .total-label {
            font-weight: bold;
            font-size: 14px;
            margin-right: 10px;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">FACTURA</h1>
                <p><strong>Cliente ID:</strong> {{ $invoice->user_id }}</p>
            </td>
            <td class="info-text">
                <strong>N° Factura:</strong> {{ $invoice->invoice_id }}<br>
                <strong>Fecha:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: right;">Precio Unit.</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->invoiceDetails as $detail)
                <tr>
                       {{-- <td>{{ $detail->product->product_id }}</td>
                       <h2>waaaa</h2> --}}
                    <td>{{ $detail->product->name }}</td>
                    <td style="text-align: center;">{{ $detail->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($detail->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($detail->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-container">
        <div class="total-box">
            <span class="total-label">TOTAL:</span>
            <span class="total-amount">${{ number_format($invoice->total, 2) }}</span>
        </div>
    </div>

</body>
</html>