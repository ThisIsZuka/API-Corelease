<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Exception;
use Carbon\Carbon;

use ReallySimpleJWT\Token;

use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Decode;

class JWT_Token
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
        try {
            $data = $request->all();
            $headers = $request->header();
            // dd($headers);
            // if(!isset($headers['token'])){
            //     dd('no');
            // }else{
            //     dd('have');
            // }
            if (!isset($headers['api-token'])) {
                return response()->json(array(
                    'Code' => '00X1',
                    'status' => 'Error',
                    'message' => 'Request header [api-token]',
                ), 401);
            }
            //  throw new Exception('Request header [api-token]');

            date_default_timezone_set("Asia/Bangkok");

            $now = Carbon::now();

            $token = $headers['api-token'][0];

            $secret = ENV('JWT_SECRET');
            // $secret = "!C0M$7uF0NdT0K@n*";

            // $result = Token::validate($token, $secret);
            // if(!$result) throw new Exception('Token is invalid');
            // dd($result);
            $Header = Token::getHeader($token, $secret);

            // Return the payload claims
            $Payload = Token::getPayload($token, $secret);
            $crate_date = new Carbon($Payload['iat']);
            $crate_date->timezone("Asia/Bangkok");
            $exp = new Carbon($Payload['exp']);
            $exp->timezone("Asia/Bangkok");

            $jwt = new Jwt($token, $secret);
            // dd($jwt->getSecret());

            if ($now > $exp) {
                // throw new Exception('Token Expired');
                return response()->json(array(
                    'Code' => '00X3',
                    'status' => 'Error',
                    'message' => 'Token Expired',
                ), 401);
            }


            return $next($request);
        } catch (Exception $e) {
            // dd($e);
            return response()->json(array(
                'Code' => '00X9',
                'status' => 'Error',
                // 'message' => $e->getMessage(),
                'message' => 'Token Invalid',
            ), 401);
        }
    }
}
