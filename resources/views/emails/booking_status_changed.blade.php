<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Status Updated</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background-color: #0d6efd; color: #fff; padding: 15px; border-radius: 6px 6px 0 0; text-align: center; }
        .btn-status { padding: 8px 12px; border-radius: 5px; color: #fff; text-decoration: none; }
        .approved { background-color: #28a745; }
        .cancelled { background-color: #dc3545; }
        .pending { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Booking Status Update</h2>
        </div>

        <p>Hello <strong>{{ $booking->customer->name }}</strong>,</p>

        <p>Your booking for <strong>{{ $booking->warehouse->name }}</strong> has been 
            <span class="btn-status {{ $status }}">
                {{ ucfirst($status) }}
            </span>.
        </p>

        <h5>Booking Details:</h5>
        <ul>
            <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('d-m-Y') }}</li>
            <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($booking->end_date)->format('d-m-Y') }}</li>
            <li><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</li>
        </ul>

        <p>Thank you for using our Warehouse Booking System.</p>

        <p>Best regards,<br> <strong>Your Warehouse Team</strong></p>
    </div>
</body>
</html>
