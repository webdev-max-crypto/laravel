<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;

//-------------------------------
//Stripe Method
//-------------------------------

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Checkout\Session;
use Stripe\Transfer;

// -------------------------------
// ADMIN CONTROLLERS
// -------------------------------
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OwnerController as AdminOwnerController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\FraudController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\AdminEscrowController as AdminEscrowController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// -------------------------------
// OWNER CONTROLLERS
// -------------------------------
use App\Http\Controllers\Owner\WarehouseController as OwnerWarehouseController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Owner\NotificationController as OwnerNotificationController;
use App\Http\Controllers\PaymentController; // Owner payments

// -------------------------------
// CUSTOMER CONTROLLERS
// -------------------------------
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\WarehouseController as CustomerWarehouseController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\ReportController as CustomerReportController;
use App\Http\Controllers\Customer\CustomerHistoryController;
use App\Http\Controllers\Customer\CustomerOrderController;

// -------------------------------
// GENERAL ROUTES
// -------------------------------




Route::get('/stripe-key-test', function () {
    dd(
        config('services.stripe.secret'),
        env('STRIPE_SECRET')
    );
});

Route::get('/owner/{id}/stripe-onboard', function ($id) {
    $owner = \App\Models\User::findOrFail($id);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $account = Account::create([
        'type' => 'express',
        'email' => $owner->email,
    ]);

    $owner->stripe_account_id = $account->id;
    $owner->save();

    $link = AccountLink::create([
        'account' => $account->id,
        'refresh_url' => route('stripe.refresh'),
        'return_url' => route('stripe.success'),
        'type' => 'account_onboarding',
    ]);

    return redirect($link->url);
})->name('owner.stripe-onboard');




Route::get('/booking/{id}/checkout', function ($id) {
    $booking = \App\Models\Booking::findOrFail($id);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = Session::create([
        'payment_method_types' => ['card'],
        'customer_email' => $booking->customer->email,
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $booking->total_amount * 100,
                'product_data' => [
                    'name' => 'Booking #' . $booking->id,
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('booking.success', $booking->id),
        'cancel_url' => route('booking.cancel', $booking->id),
    ]);

    return redirect($session->url);
});

Route::get('/booking/{id}/success', function ($id) {
    $booking = \App\Models\Booking::findOrFail($id);

    $booking->payment_status = 'paid';
    $booking->payment_ref = request('session_id'); 
    $booking->owner_amount = $booking->total_amount - $booking->admin_commission;
    $booking->save();

    return "Payment successful! Admin balance me hold ho gaya.";
})->name('booking.success');




Route::post('/booking/{id}/release', function ($id) {
    $booking = \App\Models\Booking::findOrFail($id);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    Transfer::create([
        'amount' => $booking->owner_amount * 100,
        'currency' => 'usd',
        'destination' => $booking->owner->stripe_account_id,
    ]);

    $booking->payment_status = 'released';
    $booking->save();

    return redirect()->back()->with('success', 'Payment released to owner!');
})->name('booking.release');





// Default home route





Route::get('/', fn() => view('welcome'));
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/register-user', [AdminController::class, 'register'])->name('admin.register');
});

// API for SMS receiving (from Android app)
Route::post('/api/sms/receive', [AdminController::class, 'receiveSMS']);

// Dashboard redirect after login
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'owner' => !$user->agreement_accepted
                    ? redirect()->route('owner.agreement')
                    : (!$user->is_verified
                        ? redirect()->route('owner.waiting')
                        : redirect()->route('owner.dashboard')),
        'customer' => redirect()->route('customer.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

// -------------------------------
// PROFILE ROUTES
// -------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/// -------------------------------
// OWNER ROUTES
// -------------------------------
Route::prefix('owner')->middleware(['auth','role:owner'])->name('owner.')->group(function () {

    // Dashboard & Agreement
    Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');
    Route::get('/agreement', [DashboardController::class, 'agreement'])->name('agreement');
    Route::post('/agreement/accept', [DashboardController::class, 'acceptAgreement'])->name('agreement.accept');
    Route::get('/waiting', fn() => view('owner.waiting'))->name('waiting');

    // Notifications
    Route::get('/notifications', [OwnerNotificationController::class,'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [OwnerNotificationController::class,'markAsRead'])->name('notifications.read');

    // Profile Management
    Route::get('/profile', [DashboardController::class, 'ownerProfile'])->name('profile');
    Route::post('/profile/update', [DashboardController::class, 'updateOwnerProfile'])->name('profile.update');
    Route::get('/delete', [DashboardController::class, 'deleteConfirm'])->name('delete.confirm');
    Route::post('/delete', [DashboardController::class, 'deleteAccount'])->name('delete');

    // Warehouses CRUD
    Route::resource('/warehouses', OwnerWarehouseController::class)->names([
        'index' => 'warehouses.index',
        'create' => 'warehouses.create',
        'store' => 'warehouses.store',
        'show' => 'warehouses.show',
        'edit' => 'warehouses.edit',
        'update' => 'warehouses.update',
        'destroy' => 'warehouses.destroy'
    ]);

    // Bookings
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings');
    Route::post('/bookings/{booking}/approve', [OwnerBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/cancel', [OwnerBookingController::class, 'cancel'])->name('bookings.cancel');

    // Payments
    Route::get('/payments', [PaymentController::class, 'ownerIndex'])->name('payments');
    Route::post('/payments/stripe/{bookingId}', [PaymentController::class, 'ownerStripePay'])->name('payments.stripe');
    Route::post('/payments/jazzcash/{bookingId}', [PaymentController::class, 'ownerJazzCashPay'])->name('payments.jazzcash');

    // **Owner Balance Page**
    Route::get('/balance', [PaymentController::class, 'ownerBalance'])->name('balances');

    // Help & Support
    Route::get('/help', fn() => view('owner.help.index'))->name('help');
    Route::post('/help', fn(\Illuminate\Http\Request $request) => redirect()->back()->with('success', 'Your message has been sent!'));
});


// -------------------------------
// ADMIN ROUTES
// -------------------------------
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class,'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [AdminNotificationController::class,'markAsRead'])->name('notifications.read');

    // Users & Owners
    Route::get('/users', [UserController::class,'index'])->name('users.index');
    Route::post('/users/{id}/block', [UserController::class,'block'])->name('users.block');
    Route::get('/users/{id}/verify-view', [UserController::class,'verifyView'])->name('users.verifyView');
    Route::post('/users/{id}/verify-final', [UserController::class,'verifyFinal'])->name('users.verifyFinal');
    Route::get('/owners', [AdminOwnerController::class,'index'])->name('owners.index');
    Route::get('/customers', [AdminCustomerController::class,'index'])->name('customers.index');

    // Warehouses
    Route::get('/warehouses', [AdminWarehouseController::class,'index'])->name('warehouses.index');
    Route::get('/warehouses/pending', [AdminWarehouseController::class,'pending'])->name('warehouses.pending');
    Route::get('/warehouses/approved', [AdminWarehouseController::class,'approved'])->name('warehouses.approved.list'); // LIST OF APPROVED
    Route::get('/warehouses/{id}', [AdminWarehouseController::class,'show'])->name('warehouses.show');
    Route::post('/warehouses/{id}/approve', [AdminWarehouseController::class,'approve'])->name('warehouses.approve'); // APPROVE SINGLE
    Route::post('/warehouses/{id}/reject', [AdminWarehouseController::class,'reject'])->name('warehouses.reject');
    // Approved warehouses list
    Route::get('/warehouses/approved', [AdminWarehouseController::class, 'approved'])->name('warehouses.approved');


    // Products / Inventory
    Route::get('/products', [AdminProductController::class,'index'])->name('products.index');

    // Orders
    Route::get('/orders', [AdminOrderController::class,'index'])->name('orders.index');
    Route::get('/bookings/active',[AdminController::class,'activeBookings'])->name('bookings.active');
    Route::get('/bookings/expired',[AdminController::class,'expiredBookings'])->name('bookings.expired');
    Route::get('/bookings',[AdminController::class,'bookings'])->name('bookings.index');
    Route::get('/admin/release/{id}', 
    [AdminOrderController::class,'releasePage'])
    ->name('admin.release.page');
     Route::post('bookings/{id}/release', [AdminBookingController::class, 'release'])
        ->name('bookings.release');
        Route::post('admin/bookings/{booking}/pay-stripe', [AdminBookingController::class, 'payStripe'])
    ->name('admin.bookings.pay-stripe');

    Route::get('admin/bookings/{booking}/release-payment', [AdminBookingController::class, 'releasePaymentPage'])
    ->name('admin.bookings.release-payment');
    


     // Release payment page
    Route::get('bookings/{id}/release', [AdminOrderController::class, 'releasePage'])
        ->name('bookings.releasePage');
        // Confirm release (POST)

Route::post('bookings/{id}/release', [AdminOrderController::class, 'confirmRelease'])
     ->name('bookings.confirmRelease');

    // Payments & Escrow
    Route::get('/payments/escrow', [AdminPaymentController::class,'escrow'])->name('payments.escrow');

    // Admin: All owners balances page
Route::get('/balances', [AdminPaymentController::class, 'ownersBalance'])
    ->name('balances');


    
    Route::post('/payments/{id}/release', [AdminPaymentController::class,'release'])->name('payments.release');
    Route::get('/escrow', [AdminEscrowController::class,'index'])->name('escrow.index');
    Route::post('/escrow/{id}/release', [AdminEscrowController::class,'release-payment'])->name('escrow.release-payment');

    // Fraud & Reviews
    Route::get('/fraud', [FraudController::class,'index'])->name('fraud.index');
    Route::post('/fraud/{id}/resolve', [FraudController::class,'resolve'])->name('fraud.resolve');
    Route::get('/reviews', [ReviewController::class,'index'])->name('reviews.index');

    // Reports & Settings
    Route::get('/reports', [AdminReportController::class,'index'])->name('reports.index');
    Route::get('/settings', [AdminSettingsController::class,'index'])->name('settings.index');
});

// -------------------------------
// CUSTOMER ROUTES
// -------------------------------
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class,'index'])->name('dashboard');

    // Profile
    Route::get('/edit', [CustomerDashboardController::class,'edit'])->name('edit');
    Route::put('/update', [CustomerDashboardController::class,'update'])->name('update');
    Route::delete('/delete', [CustomerDashboardController::class,'destroy'])->name('delete');

    // Warehouse booking flow
    Route::get('/booking/{warehouse}', [CustomerBookingController::class,'create'])->name('booking.create');
    Route::post('/booking/{warehouse}', [CustomerBookingController::class,'store'])->name('booking.store');

    Route::get('{id}/book', [CustomerWarehouseController::class,'book'])->name('warehouses.book');
    Route::post('{id}/calculate', [CustomerWarehouseController::class,'calculate'])->name('warehouses.calculate');
    Route::post('{id}/agreement', [CustomerWarehouseController::class,'agreement'])->name('warehouses.agreement');
    Route::post('{id}/final-confirm', [CustomerWarehouseController::class,'finalConfirm'])->name('warehouses.finalConfirm');

    // Payment routes
    Route::get('/payment/{booking}', [CustomerWarehouseController::class,'payment'])->name('payment');
    Route::post('/payment/{booking}', [CustomerWarehouseController::class,'paymentStore'])->name('payment.store');

    // Booking History
    Route::get('/bookings', [CustomerBookingController::class,'index'])->name('bookings');
    Route::post('/booking/{id}/qr', [CustomerBookingController::class,'generateQr'])->middleware('auth');
    Route::post('/booking/{id}/confirm-goods', [CustomerBookingController::class,'confirmGoods']);

    // Customer full history
    Route::get('/history', [CustomerHistoryController::class,'index'])->name('history');

    // Support
    Route::get('/support', fn() => view('customer.support.index'))->name('support');

    // Notifications
    Route::get('/notifications', [CustomerNotificationController::class,'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [CustomerNotificationController::class,'markAsRead'])->name('notifications.read');

    // Reports
    Route::get('/warehouse/{id}/report', [CustomerReportController::class,'create'])->name('report.create');
    Route::post('/warehouse/{id}/report', [CustomerReportController::class,'store'])->name('report.store');

    // Orders
    Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('customer.checkout');
    Route::post('/order/place', [CustomerOrderController::class, 'placeOrder'])->name('customer.order.place');
    Route::get('/payment/instructions/{orderId}', [CustomerOrderController::class, 'paymentInstructions'])->name('customer.payment.instructions');
    Route::get('customer/booking/{id}/invoice', [App\Http\Controllers\Customer\BookingController::class, 'invoice'])
    ->name('customer.booking.invoice');

    
    Route::get('/order/status/{orderId}', [CustomerOrderController::class, 'orderStatus'])->name('customer.order.status');
});


// -------------------------------
// AUTH ROUTES
// -------------------------------
require __DIR__.'/auth.php';
