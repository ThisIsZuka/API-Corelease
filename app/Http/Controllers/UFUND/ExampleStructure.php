<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Http\Controllers\UFUND\Error_Exception;

use App\Models\APPLICATION;
use App\Models\SETUP_COMPANY_BRANCH;

class ExampleStructure extends BaseController
{
    public $Error_Exception;
    public $DateStr;
    public $Date;

    public function __construct()
    {
        $this->Error_Exception = new Error_Exception;
        $this->DateStr = Carbon::now(new DateTimeZone('Asia/Bangkok'))->format('Y-m-d H:i:s');
        $this->Date = Carbon::now(new DateTimeZone('Asia/Bangkok'));
    }

    public function New_ExampleStructure(Request $request)
    {
        try{

        }catch(Exception $e){
            return $this->Error_Exception->Msg_error($e);
        }

    }
}