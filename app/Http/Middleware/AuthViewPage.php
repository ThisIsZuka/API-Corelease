<?php

namespace App\Http\Middleware;

use Exception;
use Closure;
use Illuminate\Http\Request;

class AuthViewPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            return $next($request);
        } catch (Exception $e) {
            // return response()->view('auth_send_sms');
        }
    }
}
