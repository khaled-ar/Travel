<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Book;
use Illuminate\Http\Request;

class BooksController extends Controller {
    /**
    * Display a listing of the resource.
    */

    public function index() {
        //
    }

    /**
    * Show the form for creating a new resource.
    */

    public function create() {
        //
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {
        $request->validate( [
            'user_id'       => [ 'required', 'int', 'exists:users,id' ],
            'destination'   => [ 'required', 'string' ],
            'total_persons' => [ 'int', 'min:2' ],
            'adults'        => [ 'required', 'int' ],
            'childs'        => [ 'required', 'int' ],
            'infants'       => [ 'required', 'int' ],
            'depart'        => [ 'required', 'date' ],
            'depart_return' => [ 'required', 'date' ],
            'currency'      => [ 'string', 'size:3' ],
        ] );

        $request->merge( [
            'price' => $request->post( 'adults' ) * 20 + $request->post( 'childs' ) * 30
        ] );

        if ( !$request->has( 'total_persons' ) ) {
            $request->merge( [
                'total_persons' => $request->post( 'adults' ) + $request->post( 'childs' ),
            ] );

        } else {

            if ( $request->post( 'total_persons' ) != $request->post( 'adults' ) + $request->post( 'childs' ) ) {
                return response()->json( [
                    'status' => 'failed',
                    'message' => 'total_persons filed must be equal adults + childs'
                ] );
            }
        }

        try {
            $book = Book::forceCreate( $request->except( 'app_key' ) );

            return response()->json( [
                'status' => 'success',
                'data' => $book
            ] );

        } catch( \Exception $e ) {

            return response()->json( [
                'status' => 'failed',
                'message' => $e->getMessage()
            ] );
        }
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    */

    public function edit( string $id ) {
        //
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, string $id ) {
        //
    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        //
    }
}
