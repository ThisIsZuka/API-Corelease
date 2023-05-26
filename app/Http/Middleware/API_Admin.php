<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class API_Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->input('sa_username');
        $password = $request->input('sa_password');
        
        if (!$username || !$password) {
            return response()->json([
                'Code' => '4100',
                'error' => 'Missing credentials'
            ], 401);
        }


        if ($username != ENV('API_USERNAME') || $username != ENV('API_PASSWORD')) {
            return response()->json([
                'Code' => '4110',
                'error' => 'Invalid credentials'
            ], 401);
        }

        return $next($request);
    }
}
