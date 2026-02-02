<?php

use App\Http\Controllers\QrScanController;

Route::post('/qr/scan', [QrScanController::class, 'scan']);
Route::post('/scan-qr', function (\Illuminate\Http\Request $request) {
    return response()->json(['status'=>'success', 'message'=>'Access granted']);
})->middleware(['auth','role:customer','check.qr']);

