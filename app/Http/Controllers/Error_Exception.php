<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use stdClass;

class Error_Exception extends BaseController
{
    public function Msg_error($e)
    {

        // dd($e->getMessage());
        
        $MsgError = [
            "1000" => [
                'status' => 'Invalid Data',
            ],
            "2000" => [
                'status' => 'Invalid Condition',
            ],
            "9000" => [
                'status' => 'System Error',
            ],
        ];

        if ($e->getPrevious() != null) {
            return response()->json(array(
                'Code' => '9000',
                'status' =>  'System Error',
                'message' => $e->getPrevious()->getMessage(),
            ));
        }

        return response()->json(array(
            'Code' => (string)$e->getCode() ?: '1000',
            'status' => isset($MsgError[(string)$e->getCode()]['status']) ? $MsgError[(string)$e->getCode()]['status'] : 'Invalid Data' ,
            'message' => $e->getMessage()
            // 'message' => 'System Error. Please try again'
        ));
    }
}