<?php

namespace App\Http\Controllers;

use App\Models\UserAPI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;


class API_USER_Auth extends Controller
{

    protected $Date;

    function __construct()
    {
        $this->Date = Carbon::now();
    }

    protected function setPasswordAttribute($value)
    {
        return Hash::make($value);
    }

    protected function responseError($message, $code)
    {
        return response()->json([
            'Code' => $code,
            'message' => $message,
            'data' => []
        ], 401);
    }

    protected function responseSuccess($token)
    {
        return response()->json([
            'Code' => '0000',
            'message' => 'Success',
            'data' => [
                'token' => $token,
            ]
        ]);
    }

    public function createUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:sqlsrv.API_Auth_User,USERNAME',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Generate a unique key for the user
            $apiKey = 'CMS-' . strtoupper(bin2hex(random_bytes(45)));
            $hashedAuth_KEY = Hash::make($apiKey);

            // Create new user using the UserAPI model
            $user = new UserAPI();
            $user->USERNAME = $request->input('username');
            $user->PASSWORD = $this->setPasswordAttribute($request->input('password'));  
            $user->Auth_KEY = $hashedAuth_KEY;
            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'Code' => '0000',
                'message' => 'Success',
                'data' => [
                    'USERNAME' => $user->USERNAME,
                    'Token' =>  $token,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '1000',
                'message' => $e->getMessage(),
            ));
        }
    }


    public function UpdateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|exists:sqlsrv.API_Auth_User,USERNAME',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Code' => '1001',
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        try {
            // Generate a unique key for the user
            $apiKey = 'CMS-' . strtoupper(bin2hex(random_bytes(45)));
            $hashedAuth_KEY = Hash::make($apiKey);
            $hashedPassword = Hash::make($request->input('password'));

            $user = UserAPI::where('USERNAME', $request->input('username'))->first();
            
            if (!$user) {
                throw new Exception("User not found.");
            }

            $user->update([
                'PASSWORD' => $hashedPassword,
                'Auth_KEY' => $hashedAuth_KEY,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'Code' => '0000',
                'message' => 'Success',
                'data' => [
                    'USERNAME' => $user->USERNAME,
                    'Token' =>  $token,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'Code' => '1000',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function generateToken(Request $request)
    {
        $credentials = [
            'USERNAME' => $request->input('username'),
            'PASSWORD' => $request->input('password'),
        ];

        $user = UserAPI::where('USERNAME', $credentials['USERNAME'])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!Hash::check($credentials['PASSWORD'], $user->PASSWORD)) {
            return response()->json([
                'Code' => '4100',
                'message' => "Unauthorized",
            ]);
        }

        $factory = auth('api')->factory();
        $factory->setTTL(43200);

        $token = auth('api')->login($user);

        return $this->responseSuccess($token);
    }

    public function refreshToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return $this->responseError('Token not provided', '4130');
        }

        try {
            auth('api')->setToken($token);
            // $newToken = auth('api')->refresh();
            $newToken = auth('api')->refresh($token);
            return $this->responseSuccess($newToken);
        } catch (JWTException $e) {
            return $this->responseError($e->getMessage(), '4100');
        }
    }
}
