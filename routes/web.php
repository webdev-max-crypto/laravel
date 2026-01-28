<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;

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
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\FraudController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

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
use App\Http\Controllers\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\WarehouseController as CustomerWarehouseController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\ReportController as CustomerReportController; // Fixed alias

// -------------------------------
// GENERAL ROUTES
// -------------------------------
Route::get('/', fn() => view('welcome'));
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/register-user', [AdminController::class, 'register'])->name('admin.register');
});

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

// -------------------------------
// OWNER ROUTES
// -------------------------------
Route::prefix('owner')->middleware(['auth','role:owner'])->name('owner.')->group(function () {

    // Dashboard & Profile
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

    // -------------------------------
    // Help & Support
    // -------------------------------
    Route::get('/help', function () {
        return view('owner.help.index'); // Blade file path
    })->name('help');

    Route::post('/help', function (\Illuminate\Http\Request $request) {
        // Optional: save message or send email
        return redirect()->back()->with('success', 'Your message has been sent!');
    });
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
    Route::get('/warehouses/{id}', [AdminWarehouseController::class,'show'])->name('warehouses.show');
    Route::post('/warehouses/{id}/approve', [AdminWarehouseController::class,'approve'])->name('warehouses.approve');
    Route::post('/warehouses/{id}/reject', [AdminWarehouseController::class,'reject'])->name('warehouses.reject');

    // Products / Inventory
    Route::get('/products', [AdminProductController::class,'index'])->name('products.index');

    // Orders
    Route::get('/orders', [AdminOrderController::class,'index'])->name('orders.index');

    // Payments
    Route::get('/payments/escrow', [AdminPaymentController::class,'escrow'])->name('payments.escrow');
    Route::post('/payments/{id}/release', [AdminPaymentController::class,'release'])->name('payments.release');

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
Route::middleware(['auth','role:customer'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/edit', [CustomerDashboardController::class, 'edit'])->name('edit');
    Route::put('/update', [CustomerDashboardController::class, 'update'])->name('update');
    Route::delete('/delete', [CustomerDashboardController::class, 'destroy'])->name('delete');

    // Bookings
    Route::get('/booking/{warehouse}', [CustomerBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{warehouse}', [CustomerBookingController::class, 'store'])->name('booking.store');

    // New warehouse booking flow
    Route::get('/warehouse/{id}/book', [CustomerWarehouseController::class, 'book'])->name('warehouses.book');
    Route::post('/warehouse/{id}/calculate', [CustomerWarehouseController::class, 'calculate'])->name('warehouses.calculate');
    Route::post('/warehouse/{id}/confirm', [CustomerWarehouseController::class, 'confirm'])->name('warehouses.confirm');
    Route::post('/warehouse/{id}/agreement', [CustomerWarehouseController::class,'agreement'])->name('warehouses.agreement');
    Route::post('/warehouse/{id}/final-confirm', [CustomerWarehouseController::class,'finalConfirm'])->name('warehouses.finalConfirm');

    // Payment
    Route::get('/payment/{booking}', [CustomerBookingController::class,'payment'])->name('payment');
    Route::post('/payment/{booking}', [CustomerBookingController::class,'paymentStore'])->name('payment.store');

    // Booking history
    Route::get('/bookings', [CustomerBookingController::class,'index'])->name('bookings');

    // Notifications
    Route::get('/notifications', [CustomerNotificationController::class,'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [CustomerNotificationController::class,'markAsRead'])->name('notifications.read');

    // Reports (fixed alias)
    Route::get('/warehouse/{id}/report', [CustomerReportController::class, 'create'])->name('report.create');
    Route::post('/warehouse/{id}/report', [CustomerReportController::class, 'store'])->name('report.store');
});

// -------------------------------
// AUTH ROUTES
// -------------------------------
require __DIR__.'/auth.php';
