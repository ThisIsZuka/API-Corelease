<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

use stdClass;

class Check_Calculator extends BaseController
{

    public function __construct()
    {
        define('FINANCIAL_MAX_ITERATIONS', 128);
        define('FINANCIAL_PRECISION', 1.0e-08);
    }


    public function getBcPresion($number) {
        $dotPosition = strpos($number, '.');
        if ($dotPosition === false) {
            return 0;
        }
        return strlen($number) - strpos($number, '.') - 1;
    }


    public function getBcRound($number, $precision = 0)
    {
        $precision = ($precision < 0)
                   ? 0
                   : (int) $precision;
        if (strcmp(bcadd($number, '0', $precision), bcadd($number, '0', $precision+1)) == 0) {
            return bcadd($number, '0', $precision);
        }
        if ($this->getBcPresion($number) - $precision > 1) {
            $number = $this->getBcRound($number, $precision + 1);
        }
        $t = '0.' . str_repeat('0', $precision) . '5';
        return $number < 0
               ? bcsub($number, $t, $precision)
               : bcadd($number, $t, $precision);
    }


    public function RATE_Excel($nper, $pmt, $pv, $fv = 0.0, $type = 0, $guess)
    {
        $rate = $guess;
        // dd($rate);
        if (abs($rate) < FINANCIAL_PRECISION) {
            $y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
        } else {
            $f = exp($nper * log(1 + $rate));
            // $f = $this->getBcRound($f,2);
            // dd($this->getBcRound($f , 2));
            // dd($f);
            $y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
        }
        $y0 = $pv + $pmt * $nper + $fv;
        $y1 = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;

        // find root by secant method
        $i  = $x0 = 0.0;
        $x1 = $rate;
        while ((abs($y0 - $y1) > FINANCIAL_PRECISION) && ($i < FINANCIAL_MAX_ITERATIONS)) {
            $rate = ($y1 * $x0 - $y0 * $x1) / ($y1 - $y0);
            $x0 = $x1;
            $x1 = $rate;

            if (abs($rate) < FINANCIAL_PRECISION) {
                $y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
            } else {
                $f = exp($nper * log(1 + $rate));
                $y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
            }

            $y0 = $y1;
            $y1 = $y;
            ++$i;
        }
        return $rate;
    }

    public function test_EF()
    {
        $nper = 12;
        $pmt = 1676.64;
        $pv = -12897.2;
        $fv = 0;
        $type = 0;
        $guess = 0.1;
        var_dump(($this->RATE_Excel($nper, $pmt, $pv, $fv, $type, $guess)*12)*100);
    }
}
