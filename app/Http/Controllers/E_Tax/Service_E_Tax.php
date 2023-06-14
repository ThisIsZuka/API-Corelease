<?php

namespace App\Http\Controllers\E_Tax;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Exceptions\CustomException;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\Jobs\Job_QueueE_TaxSend;

class Service_E_Tax extends BaseController
{

    // Production
    public static $E_TAX_APIKey;

    public function __construct()
    {
        self::$E_TAX_APIKey = config('global_variable.E_TAX_APIKey');
    }

    public function Post_ETax(Request $request)
    {
        $GData = $request->all();

        try {
            $DB_E_TAX = DB::connection('sqlsrv_e_tax')->table('dbo.E_TAX_Header')
                ->select('E_TAX_HEADER_ID')
                // ->where('E_TAX_HEADER_ID', '1')
                ->get();

            foreach ($DB_E_TAX as $key => $val) {
                Job_QueueE_TaxSend::dispatch($val->E_TAX_HEADER_ID);
            }

            return 1;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
