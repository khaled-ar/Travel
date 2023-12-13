<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use App\Models\Api\Payments;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class BookingPaymentsController extends Controller {

    public function create( Request $request, Book $book ) {
        Stripe::setApiKey( config( 'services.stripe.secret_key' ) );

        $token = $request->input( 'stripeToken' );

        $data = Charge::create( [
            'amount' => $book->price,
            'currency' => $book->currency,
            'source' => $token,
        ] );

        $payment = Payments::forceCreate( [
            'book_id' => $book->id,
            'currency' => $book->currency,
            'method' => $data->method,
            'status' => 'completed',
            'transaction_id' => $data->id,
            'transaction_data' => $data->data,
        ] );

        return response()->json( [
            'status' => 'success',
            'data' => $payment
        ] );
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        $book = Book::with( 'user' )->findOrFail( $id );
        return view( 'payment', compact( 'book' ) );
    }
}
