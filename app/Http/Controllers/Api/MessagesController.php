<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        return response()->json( Message::paginate() );
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $request->merge( [ 'user_id' => Auth::id() ] );

        $data = $request->validate( [
            'full_name' => [ 'required', 'string' ],
            'email' => [ 'required', 'email' ],
            'subject' => [ 'required', 'string' ],
            'message' => [ 'required', 'string' ]
        ] );

        try {
            $message = Message::forceCreate( $data );
            return response()->json( [
                'status' => 'success',
                'data' => $message
            ] );

        } catch( \Exception $e ) {
            return response()->json( [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null
            ] );
        }
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        $message = Message::where( 'id', $id )->first();
        return response()->json( [ 'data' => $message ] );
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {

        $message = Message::findOrFail( $id )->delete();

        if ( !$message ) {
            return response()->json( [ 'status' => 'failed' ] );
        }

        return response()->json( [ 'status' => 'success' ] );
    }
}
