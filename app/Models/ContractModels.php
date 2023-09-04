<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ContractModels {
    function search_contract($CONTRACT_ID) {
        $db = DB::connection('k2prd');
        $ContractInfo = $db->select('EXEC [dbo].[SP_CONTRACT_INFO_BY_APPID] @APP_ID = ?', [$CONTRACT_ID]);
        
        if (!empty($ContractInfo)) {
            $contract_arr[0] = [
                "userID" => $ContractInfo[0]->APP_ID,
                "ContractStart" => $ContractInfo[0]->CONTRACT_START,
                "ContractEnd" => $ContractInfo[0]->CONTRACT_END,
                "ContractStatus" => $ContractInfo[0]->CONTRACT_STATUS,
                "WarrantyStatus" => $ContractInfo[0]->INSURANCE_STATUS
            ];

            $contractInfoCount = count($ContractInfo);
            for ($i = 0;$i < $contractInfoCount;$i++) {
                if ($ContractInfo[$i]->PRODUCT_TYPE != 'Branch') {
                    $contract_arr[0][$ContractInfo[$i]->PRODUCT_TYPE] = [
                        "sku" => $ContractInfo[$i]->MODEL_NUMBER,
                        "name" => $ContractInfo[$i]->PRODUCT_NAME,
                        "imageURL" => "",
                        "price" => round($ContractInfo[$i]->PRODUCT_PRICE, 3),
                        "detail" => ""
                    ];
                } else {
                    $contract_arr[0][$ContractInfo[$i]->PRODUCT_TYPE] = [
                        "branchID" => (int) $ContractInfo[$i]->PRODUCT_PRICE,
                        "name" => $ContractInfo[$i]->PRODUCT_NAME,
                        "location" => $ContractInfo[$i]->MODEL_NUMBER
                    ];
                }
            }

            return $contract_arr;
        } else {
            return array();
        }
    }
}