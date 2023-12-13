<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller {

    public function signup( Request $request ) {

        $user = $request->validate( [
            'full_name' => [ 'required', 'string', 'min:10', 'max:50', 'unique:users,full_name' ],
            'email' => [ 'required', 'email', 'unique:users,email' ],
            'phone' => [ 'required', 'regex:/[0-9]{10}/', 'unique:users,phone' ],
            'password' => [ 'required', 'string', 'min:8', 'max:20', 'confirmed' ],
            'terms' => [ 'required', 'accepted' ],
        ] );

        unset( $user[ 'terms' ], $user[ 'password_confirmation' ] );

        try {

            $user = User::create( $user );
            event( new Registered( $user ) );

            return response()->json( [
                'registration_status' => 'success',
                'data' => $user
            ] );

        } catch( \Exception $e ) {

            return response()->json( [
                'registration_status' => 'failed',
                'message' => $e->getMessage()
            ] );
        }
    }

    public function login( Request $request ) {

        $credentials = $request->only( 'email', 'password' );

        if ( !Auth::attempt( $credentials ) ) {
            return response()->json( [
                'login_status' => 'failed'
            ] );
        }

        $user = Auth::user();
        $token = $user->createToken( $request->userAgent() );

        return response()->json( [
            'login_status' => 'success',
            'token' => $token->plainTextToken,
            'data' => $user
        ] );
    }

    public function do() {

        $query_data = explode( '/', Session::get( 'url' )[ 'intended' ] );
        $user = User::where( 'id', $query_data[ 5 ] )
        ->update( [ 'email_verified_at' => now() ] );
        if ( !$user ) {
            return response()->json( [ 'message' => 'The email has not been verified' ] );
        }
        return response()->json( [ 'message' => 'The email has been verified successfully' ] );

    }
}
