<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\FraudController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\AdminController;

// OWNER CONTROLLERS
use App\Http\Controllers\Owner\WarehouseController as OwnerWarehouseController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\PaymentController; // <- Main PaymentController for owner

// CUSTOMER CONTROLLER
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\BookingController as CustomerBookingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Customer\WarehouseController;



// Owner Help & Support
Route::get('/help', [DashboardController::class, 'help'])->name('owner.help');


// -------------------------------
// OWNER ROUTES
// -------------------------------
Route::prefix('owner')->middleware(['auth','role:owner'])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
    Route::get('/agreement', [DashboardController::class, 'agreement'])->name('owner.agreement');
    Route::post('/agreement/accept', [DashboardController::class, 'acceptAgreement'])->name('owner.agreement.accept');
    Route::get('/waiting', fn() => view('owner.waiting'))->name('owner.waiting');

    Route::get('/profile', [DashboardController::class, 'ownerProfile'])->name('owner.profile');
    Route::post('/profile/update', [DashboardController::class, 'updateOwnerProfile'])->name('owner.profile.update');
    Route::get('/delete', [DashboardController::class, 'deleteConfirm'])->name('owner.delete.confirm');
    Route::post('/delete', [DashboardController::class, 'deleteAccount'])->name('owner.delete');

    // Owner Warehouses
    Route::get('/warehouses', [OwnerWarehouseController::class,'index'])->name('owner.warehouses.index');
    Route::get('/warehouses/create', [OwnerWarehouseController::class,'create'])->name('owner.warehouses.create');
    Route::post('/warehouses', [OwnerWarehouseController::class,'store'])->name('owner.warehouses.store');
    Route::get('/warehouses/{id}', [OwnerWarehouseController::class,'show'])->name('owner.warehouses.show');
    Route::get('/warehouses/{id}/edit', [OwnerWarehouseController::class,'edit'])->name('owner.warehouses.edit');
    Route::put('/warehouses/{id}', [OwnerWarehouseController::class,'update'])->name('owner.warehouses.update');
    Route::delete('/warehouses/{id}', [OwnerWarehouseController::class,'destroy'])->name('owner.warehouses.destroy');

    // Owner Bookings
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('owner.bookings');
    Route::post('/bookings/{booking}/approve', [OwnerBookingController::class, 'approve'])->name('owner.bookings.approve');
    Route::post('/bookings/{booking}/cancel', [OwnerBookingController::class, 'cancel'])->name('owner.bookings.cancel');

    // Owner Payments
    Route::get('/payments', [PaymentController::class, 'ownerIndex'])->name('owner.payments');

});

// -------------------------------
// ADMIN ROUTES
// -------------------------------
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class,'index'])->name('users.index');
    Route::post('/users/{id}/block', [UserController::class,'block'])->name('users.block');
    Route::get('/users/{id}/verify-view', [UserController::class,'verifyView'])->name('users.verifyView');
    Route::post('/users/{id}/verify-final', [UserController::class,'verifyFinal'])->name('users.verifyFinal');

    // Warehouse Approval
    Route::get('/warehouses/pending', [AdminWarehouseController::class,'pending'])->name('warehouses.pending');
    Route::get('/warehouses/{id}', [AdminWarehouseController::class,'show'])->name('warehouses.show');
    Route::post('/warehouses/{id}/approve', [AdminWarehouseController::class,'approve'])->name('warehouses.approve');
    Route::post('/warehouses/{id}/reject', [AdminWarehouseController::class,'reject'])->name('warehouses.reject');

    // Admin Payments
    Route::get('/payments/escrow', [AdminPaymentController::class,'escrow'])->name('payments.escrow');
    Route::post('/payments/{id}/release', [AdminPaymentController::class,'release'])->name('payments.release');

    // Fraud & Reviews
    Route::get('/fraud', [FraudController::class,'index'])->name('fraud.index');
    Route::post('/fraud/{id}/resolve', [FraudController::class,'resolve'])->name('fraud.resolve');
    Route::get('/reviews', [ReviewController::class,'index'])->name('reviews.index');
});

/// -------------------------------
// CUSTOMER ROUTES
// -------------------------------
Route::middleware(['auth','role:customer'])->group(function () {

    // Customer Dashboard
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
        ->name('customer.dashboard');

    // Edit profile
    Route::get('/customer/edit', [CustomerDashboardController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/update', [CustomerDashboardController::class, 'update'])->name('customer.update');
    Route::delete('/customer/delete', [CustomerDashboardController::class, 'destroy'])->name('customer.delete');

    // Booking Routes
    Route::get('/customer/booking/{warehouse}', [CustomerBookingController::class, 'create'])
        ->name('customer.booking.create');

    Route::post('/customer/booking/{warehouse}', [CustomerBookingController::class, 'store'])
        ->name('customer.booking.store');

     Route::get('/warehouse/{id}/book',
        [Customer\WarehouseController::class,'book']
    )->name('customer.warehouses.book');

    Route::post('/warehouse/{id}/calculate',
        [Customer\WarehouseController::class,'calculate']
    )->name('customer.warehouses.calculate');

    Route::post('/warehouse/{id}/confirm',
        [Customer\WarehouseController::class,'confirm']
    )->name('customer.warehouses.confirm');

    // Report Routes
    Route::get('/customer/warehouse/{id}/report', [ReportController::class, 'create'])
        ->name('customer.report.create');
    Route::post('/customer/warehouse/{id}/report', [ReportController::class, 'store'])
        ->name('customer.report.store');
});


// -------------------------------
// AUTH, REGISTER, DASHBOARD
// -------------------------------
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', fn() => view('welcome'));
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
        'owner' => !$user->agreement_accepted ? redirect()->route('owner.agreement')
                  : (!$user->is_verified ? redirect()->route('owner.waiting') : redirect()->route('owner.dashboard')),
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
// AUTH ROUTES
// -------------------------------
require __DIR__.'/auth.php';
