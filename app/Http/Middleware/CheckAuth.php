<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Session;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $auth_session = \ Session::get('auth');
      if (empty($auth_session)) {
        return redirect('login');
      }
        return $next($request);
    }

    public function terminate($request, $response)
     {
       // $auth_session = \ Session::get('auth');
       // if (empty($auth_session)) {
       //   return redirect('login');
       // }
     }
}
