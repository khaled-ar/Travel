<?php

use App\Http\Controllers\Api\BookingPaymentsController;
use App\Http\Controllers\Api\BooksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\MessagesController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['guest', 'api_app_key'])->group(function() {
    Route::post( '/users/signup', [ UsersController::class, 'signup' ] )
    ->name( 'users.signup' );

    Route::post( '/users/login', [ UsersController::class, 'login' ] )
    ->name( 'users.login' );
});

Route::middleware('api_app_key')->group(function() {
    Route::post('/messages/store', [MessagesController::class, 'store']);
    Route::get('/messages/get-all', [MessagesController::class, 'index']);
    Route::get('/messages/{id}', [MessagesController::class, 'show']);
    Route::delete('/messages/{id}', [MessagesController::class, 'destroy']);
});

Route::middleware(['auth','api_app_key'])->group(function() {
    Route::post('/books/store', [BooksController::class, 'store']);
});
