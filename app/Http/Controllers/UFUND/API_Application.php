<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use stdClass;


class API_Application extends BaseController
{
    public function New_Application(Request $request)
    {
        $data = $request->all();

        $validate = [
            "BRANCH_TYPE" => [
                'message' => 'Request Parameter [BRANCH_TYPE]',
                'numeric' => true,
            ],
            "BRANCH_ID" => [
                'message' => 'Request Parameter [BRANCH_ID]',
                'numeric' => true,
            ],
            "TAX_ID" => [
                'message' => 'Request Parameter [TAX_ID]',
                'numeric' => true,
            ]
        ];
    }
}
