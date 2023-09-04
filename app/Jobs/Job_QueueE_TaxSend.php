<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Job_QueueE_TaxSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $ETaxID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ETaxID)
    {
        $this->ETaxID = $ETaxID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ETaxID = $this->ETaxID;
        $this->MainFN($ETaxID);
        sleep(1);
    }


    function MainFN($ETaxID)
    {

        $DB_E_TAX = DB::connection('sqlsrv_e_tax')->table('dbo.E_TAX_Header')
            ->select('*')
            ->where('E_TAX_HEADER_ID', $ETaxID)
            ->get();


        foreach ($DB_E_TAX as $key => $val) {

            $ServiceCode = 'S03';
            $SendMail = ($val->H27_SEND_EMAIL  === null || $val->H27_SEND_EMAIL  === '') ? 'N' : $val->H27_SEND_EMAIL;

            $dataTextContent = $this->SetupData($val);

            $fileTextContent = $this->WriteFileCSV($dataTextContent, "FileDocID_{$val->H4_DOCUMENT_ID}");

            // // Dump and die the content of the file
            // $fileDD = file_get_contents($fileTextContent->getPathname());
            // dd($fileDD);


            if (!empty($val->PDF_Base64)) {
                $filePDFContent = $this->WriteFilePDFfromBase64($val->PDF_Base64, "FileDocID_{$val->H4_DOCUMENT_ID}.pdf");
                // dd($filePDFContent->getPathname());
            }


            // Send the HTTP request with the file attached
            $httpRequest = Http::withHeaders([
                'Authorization' => ENV('E_TAX_Authorization'),
            ]);

            $httpRequest->attach(
                "TextContent",
                $fileTextContent->get(),
                $fileTextContent->getClientOriginalName()
            );

            if (isset($filePDFContent)) {
                $ServiceCode = 'S06';
                $httpRequest->attach(
                    "PDFContent",
                    $filePDFContent->get(),
                    $filePDFContent->getClientOriginalName()
                );
            }

            $response = $httpRequest->post('https://uatservice-etax.one.th/etaxdocumentws/etaxsigndocument', [
                'SellerTaxId' => '0107557000462',
                'SellerBranchId' => '00000',
                'APIKey' => ENV('E_TAX_APIKey'),
                'UserCode' => 'com7test',
                'AccessKey' => 'P@ssw0rd',
                'ServiceCode' => $ServiceCode,
                'SendMail' => $SendMail,
            ]);

            $res = json_decode($response->body());


            $this->InsertDatabase($val, $res);

            // Remove the temporary file
            // unlink($fileTextContent->getPathname());
        }
    }


    function InsertDatabase($val, $res)
    {
        DB::connection('sqlsrv_e_tax')->table('dbo.E_TAX_Header')
            ->where('E_TAX_HEADER_ID', $val->E_TAX_HEADER_ID)
            ->where('H4_DOCUMENT_ID', $val->H4_DOCUMENT_ID)
            ->update([
                'STATUS_SEND' => 1,
            ]);


        DB::connection('sqlsrv_e_tax')->table('dbo.LOG_E_TAX')->insert([
            'E_TAX_HEADER_ID' => $val->E_TAX_HEADER_ID,
            'DOCUMENT_ID' => $val->H4_DOCUMENT_ID,
            'STATUS' => isset($res->status) ? $res->status : null,
            'TransactionCode' => isset($res->transactionCode) ? $res->transactionCode : null,
            'errorCode' => isset($res->errorCode) ? $res->errorCode : null,
            'errorMessage' => isset($res->errorMessage) ? $res->errorMessage : null,
            'xmlURL' => isset($res->xmlURL) ? $res->xmlURL : null,
            'pdfURL' => isset($res->pdfURL) ? $res->pdfURL : null,
        ]);
    }


    function WriteFileCSV($data_quoted, $fileName)
    {
        // Create a temporary file with the text contents
        $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
        $tempFile = fopen($tempFilePath, 'w');

        // Write the data rows
        foreach ($data_quoted as $row) {
            $line = implode(',', $row) . PHP_EOL;
            fwrite($tempFile, $line);
        }

        fclose($tempFile);
        $mimeType = 'text/csv';

        // Save the temporary file to storage
        // Storage::disk('public')->put("E_tax/{$fileName}.csv", file_get_contents($tempFilePath));

        $file = new UploadedFile($tempFilePath, $fileName, $mimeType, 0, true);

        // Schedule the temporary file to be deleted after the script execution is completed
        register_shutdown_function(function () use ($tempFilePath) {
            unlink($tempFilePath);
        });

        return $file;
    }

    function WriteFilePDFfromBase64($base64Pdf, $fileName)
    {
        // Decode the base64-encoded PDF
        $decodedPdf = base64_decode($base64Pdf);

        // Create a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf');

        // Write the decoded PDF content to the temporary file
        file_put_contents($tempFilePath, $decodedPdf);

        // Save the temporary file to storage
        // Storage::disk('public')->put("E_tax/{$fileName}", file_get_contents($tempFilePath));

        // Create an UploadedFile instance
        $fileName =  $fileName;
        $mimeType = 'application/pdf';
        $file = new UploadedFile($tempFilePath, $fileName, $mimeType, 0, true);

        // Schedule the temporary file to be deleted after the script execution is completed
        register_shutdown_function(function () use ($tempFilePath) {
            unlink($tempFilePath);
        });

        return $file;
    }


    function removeDoubleQuotes(&$value)
    {
        if (is_string($value)) {
            $value = str_replace('"', '', $value);
        }
    }


    function add_quotes($data)
    {
        $add_quotes = function ($value) {
            return '"' . $value . '"';
        };

        // Apply the function to each element of the nested arrays
        $data_quoted = array_map(function ($row) use ($add_quotes) {
            return array_map($add_quotes, $row);
        }, $data);

        return $data_quoted;
    }


    function SetupData($val)
    {

        // dd($DB_E_TAX_Line);

        $C = ["C", $val->C2_SELLER_TAX_ID, $val->C3_SELLER_BRANCH_ID, $val->C4_FILE_NAME];
        $H = [
            "H",
            $val->H2_DOCUMENT_TYPE_CODE,
            $val->H3_DOCUMENT_NAME,
            $val->H4_DOCUMENT_ID,
            Carbon::parse($val->H5_DOCUMENT_ISSUE_DTM)->format('Y-m-d\TH:i:s'),
            $val->H6_CREATE_PURPOSE_CODE,
            $val->H7_CREATE_PURPOSE,
            $val->H8_ADDITIONAL_REF_ASSIGN_ID,
            $val->H9_ADDITIONAL_REF_ISSUE_DTM,
            $val->H10_ADDITIONAL_REF_TYPE_CODE,
            $val->H11_ADDITIONAL_REF_DOCUMENT_NAME,
            $val->H12_DELIVERY_TYPE_CODE,
            $val->H13_BUYER_ORDER_ASSIGN_ID,
            $val->H14_BUYER_ORDER_ISSUE_DTM != "" ? Carbon::parse($val->H14_BUYER_ORDER_ISSUE_DTM)->format('Y-m-d\TH:i:s') : null,
            $val->H15_BUYER_ORDER_REF_TYPE_CODE,
            $val->H16_DOCUMENT_REMARK,
            $val->H17_VOUCHER_NO,
            $val->H18_SELLER_CONTACT_PERSON_NAME,
            $val->H19_SELLER_CONTACT_DEPARTMENT_NAME,
            $val->H20_SELLER_CONTACT_URIID,
            $val->H21_SELLER_CONTACT_PHONE_NO,
            $val->H22_FLEX_FIELD,
            $val->H23_SELLER_BRANCH_ID,
            $val->H24_SOURCE_SYSTEM,
            $val->H25_ENCRYPT_PASSWORD,
            $val->H26_PDF_TEMPLATE_ID,
            $val->H27_SEND_EMAIL,
            $val->H28_PDF_NAME,
        ];
        $B = [
            "B",
            $val->B2_BUYER_ID,
            $val->B3_BUYER_NAME,
            $val->B4_BUYER_TAX_ID_TYPE,
            $val->B5_BUYER_TAX_ID,
            $val->B6_BUYER_BRANCH_ID,
            $val->B7_BUYER_CONTACT_PERSON_NAME,
            $val->B8_BUYER_CONTACT_DEPARTMENT_NAME,
            $val->B9_BUYER_URIID,
            $val->B10_BUYER_CONTACT_PHONE_NO,
            $val->B11_BUYER_POST_CODE,
            $val->B12_BUYER_BUILDING_NAME,
            $val->B13_BUYER_BUILDING_NO,
            $val->B14_BUYER_ADDRESS_LINE1,
            $val->B15_BUYER_ADDRESS_LINE2,
            $val->B16_BUYER_ADDRESS_LINE3,
            $val->B17_BUYER_ADDRESS_LINE4,
            $val->B18_BUYER_ADDRESS_LINE5,
            $val->B19_BUYER_STREET_NAME,
            $val->B20_BUYER_CITY_SUB_DIV_ID,
            $val->B21_BUYER_CITY_SUB_DIV_NAME,
            $val->B22_BUYER_CITY_ID,
            $val->B23_BUYER_CITY_NAME,
            $val->B24_BUYER_COUNTRY_SUB_DIV_ID,
            $val->B25_BUYER_COUNTRY_SUB_DIV_NAME,
            $val->B26_BUYER_COUNTRY_ID,
        ];
        $F = [
            "F",
            $val->F2_LINE_TOTAL_COUNT,
            Carbon::parse($val->F3_DELIVERY_OCCUR_DTM)->format('Y-m-d\TH:i:s'),
            $val->F4_INVOICE_CURRENCY_CODE,
            $val->F5_TAX_TYPE_CODE_1,
            $val->F6_TAX_CAL_RATE_1,
            $val->F7_BASIS_AMOUNT_1,
            $val->F8_BASIS_CURRENCY_CODE_1,
            $val->F9_TAX_CAL_AMOUNT_1,
            $val->F10_TAX_CAL_CURRENCY_CODE_1,
            $val->F11_TAX_TYPE_CODE_2,
            $val->F12_TAX_CAL_RATE_2,
            $val->F13_BASIS_AMOUNT_2,
            $val->F14_BASIS_CURRENCY_CODE_2,
            $val->F15_TAX_CAL_AMOUNT_2,
            $val->F16_TAX_TYPE_CODE_2,
            $val->F17_TAX_TYPE_CODE_3,
            $val->F18_TAX_CAL_RATE_3,
            $val->F19_BASIS_AMOUNT_3,
            $val->F20_BASIS_CURRENCY_CODE_3,
            $val->F21_TAX_CAL_AMOUNT_3,
            $val->F22_TAX_CAL_CURRENCY_CODE_3,
            $val->F23_TAX_TYPE_CODE_4,
            $val->F24_TAX_CAL_RATE_4,
            $val->F25_BASIS_AMOUNT_4,
            $val->F26_BASIS_CURRENCY_CODE_4,
            $val->F27_TAX_CAL_AMOUNT_4,
            $val->F28_TAX_CAL_CURRENCY_CODE_4,
            $val->F29_ALLOWANCE_CHARGE_IND,
            $val->F30_ALLOWANCE_ACTUAL_AMOUNT,
            $val->F31_ALLOWANCE_ACTUAL_CURRENCY_CODE,
            $val->F32_ALLOWANCE_REASON_CODE,
            $val->F33_ALLOWANCE_REASON,
            $val->F34_PAYMENT_TYPE_CODE,
            $val->F35_PAYMENT_DESCRIPTION,
            $val->F36_PAYMENT_DUE_DTM != "" ? Carbon::parse($val->F36_PAYMENT_DUE_DTM)->format('Y-m-d\TH:i:s') : null,
            $val->F37_ORIGINAL_TOTAL_AMOUNT,
            $val->F38_ORIGINAL_TOTAL_CURRENCY_CODE,
            $val->F39_LINE_TOTAL_AMOUNT,
            $val->F40_LINE_TOTAL_CURRENCY_CODE,
            $val->F41_ADJUSTED_INFORMATION_AMOUNT,
            $val->F42_ADJUSTED_INFORMATION_CURRENCY_CODE,
            $val->F43_ALLOWANCE_TOTAL_AMOUNT,
            $val->F44_ALLOWANCE_TOTAL_CURRENCY_CODE,
            $val->F45_CHARGE_TOTAL_AMOUNT,
            $val->F46_CHARGE_TOTAL_CURRENCY_CODE,
            $val->F47_TAX_BASIS_TOTAL_AMOUNT,
            $val->F48_TAX_BASIS_TOTAL_CURRENCY_CODE,
            $val->F49_TAX_TOTAL_AMOUNT,
            $val->F50_TAX_TOTAL_CURRENCY_CODE,
            $val->F51_GRAND_TOTAL_AMOUNT,
            $val->F52_GRAND_TOTAL_CURRENCY_CODE,
            $val->F53_TERM_PAYMENT,
            $val->F54_WITHHOLDINGTAX_TYPE1,
            $val->F55_WITHHOLDINGTAX_DESCRIPTION1,
            $val->F56_WITHHOLDINGTAX_RATE1,
            $val->F57_WITHHOLDINGTAX_BASIS_AMOUNT1,
            $val->F58_WITHHOLDINGTAX_TAX_AMOUNT1,
            $val->F59_WITHHOLDINGTAX_TYPE2,
            $val->F60_WITHHOLDINGTAX_DESCRIPTION2,
            $val->F61_WITHHOLDINGTAX_RATE2,
            $val->F62_WITHHOLDINGTAX_BASIS_AMOUNT2,
            $val->F63_WITHHOLDINGTAX_TAX_AMOUNT2,
            $val->F64_WITHHOLDINGTAX_TYPE3,
            $val->F65_WITHHOLDINGTAX_DESCRIPTION3,
            $val->F66_WITHHOLDINGTAX_RATE3,
            $val->F67_WITHHOLDINGTAX_BASIS_AMOUNT3,
            $val->F68_WITHHOLDINGTAX_TAX_AMOUNT3,
            $val->F69_WITHHOLDINGTAX_TOTAL_AMOUNT,
            $val->F70_ACTUAL_PAYMENT_TOTAL_AMOUNT,
            $val->F71_DOCUMENT_REMARK1,
            $val->F72_DOCUMENT_REMARK2,
            $val->F73_DOCUMENT_REMARK3,
            $val->F74_DOCUMENT_REMARK4,
            $val->F75_DOCUMENT_REMARK5,
            $val->F76_DOCUMENT_REMARK6,
            $val->F77_DOCUMENT_REMARK7,
            $val->F78_DOCUMENT_REMARK8,
            $val->F79_DOCUMENT_REMARK9,
            $val->F80_DOCUMENT_REMARK10,
            $val->F81_DOCUMENT_REMARK11,
        ];

        $T = ["T", $val->T2_TOTAL_DOCUMENT_COUNT,];

        $data = [
            $C,
            $H,
            $B,
            $F,
            $T,
        ];


        $DB_E_TAX_Line = DB::connection('sqlsrv_e_tax')->table('dbo.E_TAX_Line_Item')
            ->select('*')
            ->where('DOCUMENT_ID',  $val->H4_DOCUMENT_ID)
            ->get();

        $L_arrays = [];
        foreach ($DB_E_TAX_Line as $val) {
            $L_arrays[] = [
                "L",
                $val->L2_LINE_ID,
                $val->L3_PRODUCT_ID,
                $val->L4_PRODUCT_NAME,
                $val->L5_PRODUCT_DESC,
                $val->L6_PRODUCT_BATCH_ID,
                Carbon::parse($val->L7_PRODUCT_EXPIRE_DTM)->format('Y-m-d\TH:i:s'),
                $val->L8_PRODUCT_CLASS_CODE,
                $val->L9_PRODUCT_CLASS_NAME,
                $val->L10_PRODUCT_ORIGIN_COUNTRY_ID,
                $val->L11_PRODUCT_CHARGE_AMOUNT,
                $val->L12_PRODUCT_CHARGE_CURRENCY_CODE,
                $val->L13_PRODUCT_ALLOWANCE_CHARGE_IND,
                $val->L14_PRODUCT_ALLOWANCE_ACTUAL_AMOUNT,
                $val->L15_PRODUCT_ALLOWANCE_ACTUAL_CURRENCY_CODE,
                $val->L16_PRODUCT_ALLOWANCE_REASON_CODE,
                $val->L17_PRODUCT_ALLOWANCE_REASON,
                $val->L18_PRODUCT_QUANTITY,
                $val->L19_PRODUCT_UNIT_CODE,
                $val->L20_PRODUCT_QUANTITY_PER_UNIT,
                $val->L21_LINE_TAX_TYPE_CODE,
                $val->L22_LINE_TAX_CAL_RATE,
                $val->L23_LINE_BASIS_AMOUNT,
                $val->L24_LINE_BASIS_CURRENCY_CODE,
                $val->L25_LINE_TAX_CAL_AMOUNT,
                $val->L26_LINE_TAX_CAL_CURRENCY_CODE,
                $val->L27_LINE_ALLOWANCE_CHARGE_IND,
                $val->L28_LINE_ALLOWANCE_ACTUAL_AMOUNT,
                $val->L29_LINE_ALLOWANCE_ACTUAL_CURRENCY_CODE,
                $val->L30_LINE_ALLOWANCE_REASON_CODE,
                $val->L31_LINE_ALLOWANCE_REASON,
                $val->L32_LINE_TAX_TOTAL_AMOUNT,
                $val->L33_LINE_TAX_TOTAL_CURRENCY_CODE,
                $val->L34_LINE_NET_TOTAL_AMOUNT,
                $val->L35_LINE_NET_TOTAL_CURRENCY_CODE,
                $val->L36_LINE_NET_INCLUDE_TAX_TOTAL_AMOUNT,
                $val->L37_LINE_NET_INCLUDE_TAX_TOTAL_CURRENCY_CODE,
                $val->L38_PRODUCT_REMARK1,
                $val->L39_PRODUCT_REMARK2,
                $val->L40_PRODUCT_REMARK3,
                $val->L41_PRODUCT_REMARK4,
                $val->L42_PRODUCT_REMARK5,
                $val->L43_PRODUCT_REMARK6,
                $val->L44_PRODUCT_REMARK7,
                $val->L45_PRODUCT_REMARK8,
                $val->L46_PRODUCT_REMARK9,
                $val->L47_PRODUCT_REMARK10,
            ];
        }

        // Insert the $L arrays into $data
        foreach ($L_arrays as $L) {
            array_splice($data, count($data) - 2, 0, [$L]);
        }

        // array_walk_recursive($data, function (&$value) {
        //     if (is_string($value)) {
        //         $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
        //     }
        // });

        // removeDoubleQuotes 
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                $value = str_replace('"', '', $value);
            }
        });


        $data_quoted = $this->add_quotes($data);

        return $data_quoted;
    }
}
