<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\API_PROSPECT_CUSTOMER;
use stdClass;

use App\Http\Controllers\Check_Calculator;
use App\Http\Controllers\NCB_ZipFile;
use App\Http\Controllers\Random_Str;

class test extends BaseController
{

    public function __construct()
    {
        // dd('456');
    }

    public function Test_API_SP(Request $request)
    {
        // $GData = $request->all();

        // $GData['tax_id'];

        // return $GData['tax_id'];
        return 'rere';
    }
}
