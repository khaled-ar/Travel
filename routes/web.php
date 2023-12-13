<?php

use App\Http\Controllers\Api\BookingPaymentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the 'web' middleware group. Make something great!
|
*/


Route::middleware('auth')->group(function() {

    Route::get('/email/verify')
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
    })->middleware('signed')
        ->name('verification.verify');

    Route::middleware('api_app_key')->post('/email/verification-notification', function (Request $request) {

        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!']);

    })->middleware('throttle:6,1')
        ->name('verification.send');

});


Route::any('login', [
        'as' => 'login',
        'uses' => '\App\Http\Controllers\Api\UsersController@do'
    ]);

Route::post('/booking/{book}/pay', [BookingPaymentsController::class, 'create'])
->name('booking.payment');

Route::get('/booking/form/{id}', [BookingPaymentsController::class, 'show']);



