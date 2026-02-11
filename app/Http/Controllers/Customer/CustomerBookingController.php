<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerBookingController extends Controller
{
    // Show booking page
    public function create($warehouse_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // Store booking after confirmation
    public function store(Request $request, $warehouse_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $customer = auth()->user();

        $data = $request->validate([
            'area' => 'required|integer|min:1',
            'items' => 'required|integer|min:1',
            'months' => 'required|integer|min:1',
            'items_detail' => 'nullable|string',
            'total_price' => 'required|numeric',
        ]);

        // Create booking
        $booking = Booking::create([
            'warehouse_id' => $warehouse->id,
            'customer_id' => $customer->id,
            'user_id' => $customer->id,
            'area' => $data['area'],
            'items' => $data['items'],
            'items_detail' => $data['items_detail'],
            'months' => $data['months'],
            'total_price' => $data['total_price'],
            'status' => 'active',
        ]);

        // Notify admin
        Notification::create([
            'user_id' => 1, // Admin
            'message' => "New booking #{$booking->id} for warehouse {$warehouse->name} by {$customer->name}"
        ]);

        return redirect()->route('customer.payment', $booking->id)
                         ->with('success', 'Booking created! Proceed to payment.');
    }

    // Generate QR code (can be called after payment or final confirm)
    public function generateQr($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        if (!$booking->qr_code) {
            $booking->qr_code = QrCode::generate(route('customer.dashboard') . '?booking=' . $booking->id);
            $booking->save();
        }

        return response()->json([
            'qr_code' => $booking->qr_code
        ]);
    }
    public function invoice($id)
{
    $booking = Booking::with(['customer','warehouse'])->findOrFail($id);
    return view('customer.invoice', compact('booking'));
}

}
