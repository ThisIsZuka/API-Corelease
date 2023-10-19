<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Exception;
use Carbon\Carbon;

use App\Models\UserAPI;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWT_Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {

        $token = $request->header('Authorization');

        if (!$token) {
            return $this->responseError('Token not provided', '4110');
        }

        try {
            if (!JWTAuth::setToken($token)->check()) {
                return $this->responseError('Token is invalid', '4130');
            }

            $payload = JWTAuth::setToken($token)->getPayload();
            $userId = $payload->get('user_id');
            $user = UserAPI::where('USER_ID', $userId)->first();
            if (!$user) {
                return $this->responseError('User not found', '4100');
            }

            return $next($request);
        } catch (TokenInvalidException $e) {
            return $this->responseError('Token is invalid', '4130');
        } catch (TokenExpiredException $e) {
            return $this->responseError('Token has expired', '4120');
        } catch (JWTException $e) {
            return $this->responseError('Token error: ' . $e->getMessage(), '4100');
        }
    }

    protected function responseError($message, $code)
    {
        return response()->json([
            'Code' => $code,
            'message' => $message,
            'data' => []
        ], 401);
    }
}
