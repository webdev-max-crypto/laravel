<!-- resources/views/customer/order-status.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <meta http-equiv="refresh" content="10"> <!-- Auto refresh every 10 seconds -->
</head>
<body>
    <h2>Order Status</h2>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Amount:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
    
    @if($order->payment_status === 'paid')
        <div style="color: green; font-size: 20px;">
            ✓ PAYMENT RECEIVED - Order Confirmed!
        </div>
        @if($order->payment)
            <p>Transaction ID: {{ $order->payment->transaction_id }}</p>
            <p>Payment Method: {{ $order->payment->payment_method }}</p>
        @endif
    @else
        <div style="color: orange; font-size: 20px;">
            ⏳ Waiting for payment...
        </div>
        <p><em>This page will auto-refresh when payment is received</em></p>
    @endif
</body>
</html>