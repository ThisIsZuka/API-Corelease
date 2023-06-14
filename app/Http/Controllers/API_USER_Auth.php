<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use stdClass;

class API_USER_Auth extends Controller
{
    function __construct()
    {
    }

    function CreateUser(Request $request)
    {
        try {

            $req = $request->all();

            // Generate a unique key for the user
            $apiKey = 'CMS-' . strtoupper(bin2hex(random_bytes(45))); // This generates a 45 characters long key
            $hashedAuth_KEY = Hash::make($apiKey);

            $username = $req['username'];
            $password = $req['password'];
            $hashedPassword = Hash::make($password);

            $CheckdupUser = DB::table('dbo.API_Auth_User')
                ->select('*')
                ->where('USERNAME', $username)
                ->get();

            if(count($CheckdupUser) > 0){
                throw new Exception('USERNAME already exists in the system');
            }

            DB::table('dbo.API_Auth_User')->insert([
                'USERNAME' => $username,
                'PASSWORD' => $hashedPassword,
                'Auth_KEY' => $hashedAuth_KEY,
                'CREATE_AT' => Carbon::now(),
            ]);

            return response()->json(array(
                'Code' => '0000',
                'message' => 'Success',
                'data' => [
                    'USERNAME' => $username,
                    'Auth_KEY' => $apiKey,
                ]
            ));
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '1000',
                'message' => $e->getMessage(),
            ));
        }
    }

    function UpdateUser(Request $request)
    {
        try {

            $req = $request->all();

            // Generate a unique key for the user
            $apiKey = 'CMS-' . strtoupper(bin2hex(random_bytes(45))); // This generates a 45 characters long key
            $hashedAuth_KEY = Hash::make($apiKey);

            $username = $req['username'];
            $password = $req['password'];
            $hashedPassword = Hash::make($password);

            DB::table('dbo.API_Auth_User')
                ->where('USERNAME',  $username)
                ->update([
                    'PASSWORD' => $hashedPassword,
                    'Auth_KEY' => $hashedAuth_KEY,
                    'UPDATE_AT' => Carbon::now(),
                ]);

            return response()->json(array(
                'Code' => '0000',
                'message' => 'Success',
                'data' => [
                    'USERNAME' => $username,
                    'Auth_KEY' => $apiKey,
                ]
            ));
        } catch (Exception $e) {
            return response()->json(array(
                'Code' => '1000',
                'message' => $e->getMessage(),
            ));
        }
    }
}
