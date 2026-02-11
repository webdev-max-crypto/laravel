<!-- resources/views/customer/payment-instructions.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Payment Instructions</title>
</head>
<body>
    <h2>Complete Your Payment</h2>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Amount:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
    
    <h3>Payment Instructions:</h3>
    <ol>
        <li>Send <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong> to:</li>
        <li>JazzCash: <strong>{{ $adminSettings->jazzcash_account ?? '03XXXXXXXXX' }}</strong></li>
        <li>After payment, your order will be automatically verified</li>
        <li>Check your order status <a href="{{ route('customer.order.status', $order->id) }}">here</a></li>
    </ol>

    <p><em>Payment verification usually takes 1-2 minutes</em></p>
</body>
</html>