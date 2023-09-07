<?php

namespace App\Http\Controllers\UFUND;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use stdClass;

use App\Http\Controllers\UFUND\Check_Calculator;

class INTEREST_EFFECTIVE extends BaseController
{
    function __construct()
    {
    }

    public function CalculateEFFECTIVE(Request $request)
    {
        try {
            $req = $request->all();
            // dd($req);

            $Check_Calculator = new Check_Calculator;
            $nper = $req['INSTALL_NUM'];
            $pmt = $req['INSTALL_AMT'];
            $pv = - ($req['HP_AMT']);
            // dd($HP_AMT);
            $fv = 0;
            $type = 0;
            $guess = 0.1;
            $EFFECTIVE = strval(round($Check_Calculator->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess) * 12, 8));

            return $EFFECTIVE;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
