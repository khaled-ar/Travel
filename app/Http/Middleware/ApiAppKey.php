<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAppKey {
    /**
    * Handle an incoming request.
    *
    * @param  \Closure( \Illuminate\Http\Request ): ( \Symfony\Component\HttpFoundation\Response )  $next
    */

    public function handle( Request $request, Closure $next ): Response {

        $request_app_key = $request->get( 'app_key' );
        $request_app_key[ 39 ] = '+';

        if ( $request_app_key !== env( 'APP_KEY' ) ) {
            return response()->json( [ 'message' => 'Forbidden' ] );
        }
        return $next( $request );
    }
}
