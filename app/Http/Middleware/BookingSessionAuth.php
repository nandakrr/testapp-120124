<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Session;


class BookingSessionAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $bookingSession = Session::get('bookingAuth');

        if ( empty($bookingSession) ) {
            return redirect('booking/signin');
        }
        return $next($request);
    }
}
