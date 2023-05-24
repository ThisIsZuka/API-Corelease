<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use stdClass;
use Illuminate\Support\Facades\Http;

class Job_QueuePostICareAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ReqData;

    public $Endpoint;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ReqData)
    {
        $this->ReqData = $ReqData;

        $this->Endpoint = 'https://irail.icare-insurance.com/staging';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

    public function NewLoan(Request $request)
    {
        try {
            $req = $request->all();
            // dd($req['APP_ID']);
            $path = 'submissions/submit/loan';

            $data = $this->SetupDataNewLoan($req['APP_ID']);

            $this->HTTPS($path, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function HTTPS($path, $Data)
    {
        // dd($Data);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => '=',
        ])->dd()->post("{$this->Endpoint}/{$path}", [$Data]);
        dd($response);
    }

    function SetupDataNewLoan($APP_ID)
    {
        $DB_APP = DB::table('dbo.APPLICATION')
            ->select('*')
            ->where('APP_ID', $APP_ID)
            ->first();

        $DB_PRODUCT = DB::table('dbo.PRODUCT')
            ->select('*')
            ->where('APP_ID', $APP_ID)
            ->first();

        $DB_CONTRACT = DB::table('dbo.CONTRACT')
            ->select('*')
            ->where('APP_ID', $APP_ID)
            ->orderByDesc('CONTRACT_ID')
            ->first();

        $DB_ADDRESS = DB::table('dbo.ADDRESS')
            ->select(
                'ADDRESS.*',
                'MT_DISTRICT.DISTRICT_NAME',
                'MT_PROVINCE.PROVINCE_NAME',
                'MT_SUB_DISTRICT.SUB_DISTRICT_NAME',
                'PERSON.AGE',
                'PERSON.BIRTHDAY',
                'PERSON.EMAIL',
                'PERSON.SEX',
                'PERSON.TAX_ID',
                'PERSON.FIRST_NAME',
                'PERSON.LAST_NAME',
                'PERSON.PHONE',
                'PERSON.PREFIX',
                'MT_PREFIX.Prefix_name',
                'PERSON.PREFIX_OTHER',
            )
            ->leftJoin('PERSON', 'PERSON.PERSON_ID', 'ADDRESS.PERSON_ID')
            ->leftJoin('MT_SUB_DISTRICT', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID', 'ADDRESS.A1_SUBDISTRICT')
            ->leftJoin('MT_DISTRICT', 'MT_DISTRICT.DISTRICT_ID', 'ADDRESS.A1_DISTRICT')
            ->leftJoin('MT_PROVINCE', 'MT_PROVINCE.PROVINCE_ID', 'ADDRESS.A1_PROVINCE')
            ->leftJoin('MT_PREFIX', 'MT_PREFIX.Prefix_ID', 'PERSON.PREFIX')
            ->where('PERSON.APP_ID', $APP_ID)
            ->first();

        // dd($DB_ADDRESS);
        if (empty($DB_APP) || empty($DB_PRODUCT) || empty($DB_CONTRACT)) {
            throw new Exception("Not Found Data");
        }

        $data = new stdClass();
        $data->document = new stdClass();
        $data->document->installmentPeriod = (int)$DB_PRODUCT->INSTALL_NUM;
        $data->document->loanAmount = (string)(number_format($DB_PRODUCT->PROD_TOTAL_AMT, 2, '.', ''));
        $data->document->loanNo = (string)$DB_CONTRACT->CONTRACT_NUMBER;

        $data->mandatory = new stdClass();
        $data->mandatory->certificateRef = null;
        $data->mandatory->effectiveDate = Carbon::parse($DB_CONTRACT->CONTRACT_START)->format('Y-m-d\TH:i:s') . '.000Z';
        $data->mandatory->expireDate = Carbon::parse($DB_CONTRACT->CONTRACT_END)->format('Y-m-d\TH:i:s') . '.000Z';
        $data->mandatory->identifier = (string)$APP_ID;
        $data->mandatory->insured = new stdClass();
        $data->mandatory->insured->address = new stdClass();
        $data->mandatory->insured->address->district = (string)$DB_ADDRESS->DISTRICT_NAME;
        $data->mandatory->insured->address->fullAddress = (string)'';
        $data->mandatory->insured->address->moo = (string)(($DB_ADDRESS->A1_MOI != '-' && $DB_ADDRESS->A1_MOI != '') ? $DB_ADDRESS->A1_MOI : null);
        $data->mandatory->insured->address->no = (string)$DB_ADDRESS->A1_NO;
        $data->mandatory->insured->address->postalCode = (string)$DB_ADDRESS->A1_POSTALCODE;
        $data->mandatory->insured->address->province = (string)$DB_ADDRESS->PROVINCE_NAME;
        $data->mandatory->insured->address->road = (string)(($DB_ADDRESS->A1_ROAD != '-' && $DB_ADDRESS->A1_ROAD != '') ? $DB_ADDRESS->A1_ROAD : null);
        $data->mandatory->insured->address->soi = (string)(($DB_ADDRESS->A1_SOI != '-' && $DB_ADDRESS->A1_SOI != '') ? $DB_ADDRESS->A1_SOI : null);
        $data->mandatory->insured->address->subDistrict = (string)$DB_ADDRESS->SUB_DISTRICT_NAME;
        $data->mandatory->insured->age = $DB_ADDRESS->AGE;
        $data->mandatory->insured->birthDate = Carbon::parse($DB_ADDRESS->BIRTHDAY)->format('Y-m-d\TH:i:s') . '.000Z';
        $data->mandatory->insured->email = $DB_ADDRESS->EMAIL;
        $data->mandatory->insured->gender = $DB_ADDRESS->SEX == '1' ? 'M' : 'F';
        $data->mandatory->insured->identificationId = '';
        $data->mandatory->insured->juristicId = '';
        $data->mandatory->insured->name = (string)$DB_ADDRESS->FIRST_NAME;
        $data->mandatory->insured->surname = (string)$DB_ADDRESS->LAST_NAME;
        $data->mandatory->insured->taxId = (string)$DB_ADDRESS->TAX_ID;
        $data->mandatory->insured->telephone = (string)$DB_ADDRESS->PHONE;
        $data->mandatory->insured->title = (string)($DB_ADDRESS->PREFIX != '4' ? $DB_ADDRESS->Prefix_name : $DB_ADDRESS->PREFIX_OTHER);
        $data->mandatory->premium = '1000.50';
        $data->mandatory->sumInsured = (string)(number_format($DB_PRODUCT->PROD_TOTAL_AMT, 2, '.', ''));

        $data->metadata = new stdClass();
        $data->metadata->additionalDocument = new stdClass();
        $data->metadata->canceling = false;
        $data->metadata->memo = (string)'';

        return $data;
    }
}
