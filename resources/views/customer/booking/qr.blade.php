<h3>Your Booking QR Code</h3>
@if($booking->qr_code)
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $booking->qr_code }}" alt="QR Code">
@endif
<p>Valid till: {{ $booking->qr_expires_at->format('d-m-Y') }}</p>
