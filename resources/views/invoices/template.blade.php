<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .company-details {
            margin-bottom: 20px;
        }
        .invoice-details {
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-details td {
            padding: 5px 0;
        }
        .customer-details {
            margin-bottom: 20px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
        }
        table.items td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .subtotal {
            text-align: right;
            margin-bottom: 10px;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .payment-info {
            margin-bottom: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ $invoice->type === 'proforma' ? 'PROFORMA INVOICE' : strtoupper($invoice->type . ' INVOICE') }}</p>
        </div>
        
        <div class="company-details">
            <h2>{{ $project->name }}</h2>
            <!-- Add more company details if available -->
        </div>
        
        <table class="invoice-details">
            <tr>
                <td width="50%"><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</td>
                <td width="50%"><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>Order Number:</strong> {{ $order->order_number }}</td>
                <td><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong> {{ ucfirst($invoice->status) }}</td>
                <td></td>
            </tr>
        </table>
        
        <div class="customer-details">
            <h3>Customer Information</h3>
            <p>
                <strong>Name:</strong> {{ $order->guest_name }}<br>
                @if($order->guest_email)
                    <strong>Email:</strong> {{ $order->guest_email }}<br>
                @endif
                @if($order->guest_phone)
                    <strong>Phone:</strong> {{ $order->guest_phone }}<br>
                @endif
            </p>
        </div>
        
        <h3>Order Items</h3>
        <table class="items">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="45%">Item</th>
                    <th width="15%">Price</th>
                    <th width="10%">Qty</th>
                    <th width="20%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product_name }}
                            @if($item->variant_details)
                                <br>
                                <small>
                                    @foreach($item->variant_details as $attribute => $value)
                                        {{ $attribute }}: {{ $value }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="subtotal">
            <p><strong>Subtotal:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>
        
        <div class="total">
            <p>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>
        
        <div class="payment-info">
            <h3>Payment Information</h3>
            <p>
                <strong>Amount Paid:</strong> Rp {{ number_format($order->amount_paid, 0, ',', '.') }}<br>
                <strong>Remaining:</strong> Rp {{ number_format($order->getRemainingAmount(), 0, ',', '.') }}
            </p>
            
            @if($order->payments->where('status', 'verified')->count() > 0)
                <p><strong>Payment History:</strong></p>
                <ul>
                    @foreach($order->payments->where('status', 'verified') as $payment)
                        <li>
                            {{ $payment->created_at->format('d M Y') }} - 
                            Rp {{ number_format($payment->amount, 0, ',', '.') }} via {{ $payment->payment_method_name }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated automatically by Invoice Automation System.</p>
        </div>
    </div>
</body>
</html>