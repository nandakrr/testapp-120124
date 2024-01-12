<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Session;


class StagingBookingSessionAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $bookingSession = Session::get('staging_bookingAuth');

        if ( empty($bookingSession) ) {
            return redirect('staging/booking/signin');
        }
        return $next($request);
    }
}
