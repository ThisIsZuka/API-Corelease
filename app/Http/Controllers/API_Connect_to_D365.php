<?php

namespace App\Http\Controllers;

use App\Http\Controllers\D365Connect\D365Connect;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class API_Connect_to_D365 extends Controller {
    function __construct()
    {
        $this->D365 = new D365Connect;
        $this->D365->setEndpoint([
            "Oauth2" => "oauth2/token",
            "k2test" => "api/Services/COM7_InterfaceServiceGroup/COM7_K2/RetrunMessage",
            "CreateFinancialDimension" => 'api/Services/MIS_InterfaceServiceGroup/MIS_FinancialDimension/CreateFinancialDimentsion'
        ]);
        $this->data = [];

        // connect MIS DB [min-project]
        $this->db = DB::connection('misdiy');
    }

    public function setToken($token) {
        $this->D365->setToken($token);
        return $this;
    }

    public function getToken() {
        return $this->D365->getToken();
    }

    public function requestToken() {
        return $this->D365->connect();
    }

    public function updateNewCategory_daily() {
        try {
            // run stored-proc
            // select new sub category today
            $this->data = $this->db->table('SubCategory')->get();

            // fire new sub category today
            for ($i = 0;$i < count($this->data);$i++) {
                $fireData = $this->data[$i];
                $dimensiontype = strtoupper('subcategory');
                $digit = strlen($fireData->ID) <= 5 ? str_repeat('0',  5 - strlen($fireData->ID)):'';
                $value = $digit . $fireData->ID;
                $description = $fireData->SubCategoryName;

                $response = Http::post($this->D365->getEndpoint('CreateFinancialDimension'), [
                    'dimensiontype' => $dimensiontype,
                    'value' => $value,
                    'description' => $description
                ]);

                var_dump($response);
            }

            // select new category today

            // fire new category today

            // select new dirived dimension today

            // fire new dirived dimension today
            
            // select new mapping param

            // fire mapping param

            // update product table by sub category id only today

            // return status and data
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}