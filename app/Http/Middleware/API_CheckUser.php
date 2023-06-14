<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class API_CheckUser
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
        $username = $request->input('username');
        $password = $request->input('password');
        $apiKey = $request->header('Authorization');
        
        if (!$username || !$password || !$apiKey) {
            return response()->json([
                'Code' => '4100',
                'error' => 'Missing credentials'
            ], 401);
        }

        // $user = User::where('USERNAME', $username)->first();
        $user = DB::table('dbo.API_Auth_User')
            ->select('*')
            ->where('USERNAME', $username)
            ->first();

        if (!$user || !Hash::check($password, $user->PASSWORD) || !Hash::check($apiKey, $user->Auth_KEY)) {
            return response()->json([
                'Code' => '4110',
                'error' => 'Invalid credentials'
            ], 401);
        }

        return $next($request);
    }
}
