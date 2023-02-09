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
use App\Http\Controllers\helper\Convert_tag_K2;

class E_Tax_TFF extends BaseController
{

    // Production
    public static $E_TAX_APIKey;

    public function __construct()
    {
        self::$E_TAX_APIKey = config('global_variable.E_TAX_APIKey');
    }

    public function MainRequest(Request $request)
    {
        $GData = $request->all();

        // dd(self::$E_TAX_APIKey);
        // dd($GData);
        $Convert_tag_K2 = new Convert_tag_K2();

        try {
            $DB_PDF_FORM = DB::table('PDF_FORM')
                ->selectRaw('SETUP_COMPANY_BRANCH.BRANCH_CODE, PDF_FORM.*')
                ->leftJoin('APPLICATION', 'PDF_FORM.APP_ID', '=', 'APPLICATION.APP_ID')
                ->leftJoin('SETUP_COMPANY_BRANCH', 'APPLICATION.P_BRANCH_ID', '=', 'SETUP_COMPANY_BRANCH.COMP_BRANCH_ID')
                ->where('PDF_ID', '1170')
                ->orderByDesc('PDF_ID')
                ->limit(1)
                ->get();


            $DB_TAX_ID = DB::table('SETUP_COMPANY')
                ->select('C_REGIST_NO')
                ->where('COMPANY_ID', '121')
                ->first();

            // dd($DB_TAX_ID->C_REGIST_NO);
            // dd($DB_PDF_FORM[0]->BRANCH_CODE);

            $base64 = $Convert_tag_K2->GET_base64_FromTag($DB_PDF_FORM[0]->PDF_NAME);

            $filename = $Convert_tag_K2->GET_fileName_FromTag($DB_PDF_FORM[0]->PDF_NAME);

            $Convert_tag_K2->ConvertToTMPFile($base64, $filename);

            $file = Storage::disk('public')->get($filename);

            $response = Http::attach('PDFContent', $file, $filename)
                ->post('127.0.0.1/API-Corelease/public/api/test_file', [
                    'SellerTaxId' => $DB_TAX_ID->C_REGIST_NO,
                    'SellerBranchId' => $DB_PDF_FORM[0]->BRANCH_CODE,
                    'APIKey' => '3UY8R84Q6LIZ5A18IDZH6JI3O63IZLJLJH01CS1OUHRTWR5VG4AED7UCTA5HKE92JJLN1R1DZT74WCDN9PI4L4JM7B62ULRNQTHJ4EO85IYUELWWVG5R7EX8AHQNQY8YNW21Y8Q5EE46P0GQUEOFY700LLOCIBOLRXG0ZVY3J9IUWQOTYBB9TJ85DKSIU8E93MIF8NQ89HRYGGNI4U7K69DZQ9H0EGG0YC2Z09O90J77COR0HCQK9W9SALBWE3E6I',
                    'UserCode' => 'com7test',
                    'AccessKey' => 'P@ssw0rd',
                    'ServiceCode' => 'S06',
                    'TextContent' => '',
                    'SendMail' => 'N',
                ]);

            $res = json_decode($response->body());

            Storage::disk('public')->delete($filename);

            return $res;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    public function test_file(Request $request)
    {
        try {
            $GData = $request->all();

            $file = $request->file('PDFContent');

            Storage::disk('public')->put('filename.pdf', file_get_contents($file));

            return response()->json(array(
                'Code' => '0000',
                'status' => 'Success',
            ));
        } catch (Exception $e) {
            // return $e->getMessage();
            return response()->json(array(
                'Code' => '0000',
                'status' => 'Success',
                'Message' => $e->getMessage(),
            ));
        }
    }
}
