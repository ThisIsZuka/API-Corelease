<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ContractModels {
    function search_contract($CONTRACT_ID) {
        $db = DB::connection('k2prd');
        $ContractInfo = $db->select('EXEC [dbo].[SP_CONTRACT_INFO_BY_APPID] @APP_ID = ?', [$CONTRACT_ID]);
        
        $contract_arr[0] = [
            "APP_ID" => $ContractInfo[0]->APP_ID,
            "APPLICATION_NUMBER" => $ContractInfo[0]->APPLICATION_NUMBER,
            "CONTRACT_NUMBER" => $ContractInfo[0]->CONTRACT_NUMBER,
            "FIRST_NAME" => $ContractInfo[0]->FIRST_NAME,
            "LAST_NAME" => $ContractInfo[0]->LAST_NAME,
            "TAX_ID" => $ContractInfo[0]->TAX_ID,
            "PHONE" => $ContractInfo[0]->PHONE,
            "EMAIL" => $ContractInfo[0]->EMAIL,
            "CONTRACT_START" => $ContractInfo[0]->CONTRACT_START,
            "CONTRACT_END" => $ContractInfo[0]->CONTRACT_END,
            "CONTRACT_STATUS" => $ContractInfo[0]->CONTRACT_STATUS,
            "INSURANCE_STATUS" => $ContractInfo[0]->INSURANCE_STATUS
        ];

        for ($i = 0;$i < count($ContractInfo);$i++) {
            $contract_arr[0] += [$ContractInfo[$i]->PRODUCT_TYPE => [
                "PRODUCT_TYPE" => $ContractInfo[$i]->PRODUCT_TYPE,
                "MODEL_NUMBER" => $ContractInfo[$i]->MODEL_NUMBER,
                "PRODUCT_NAME" => $ContractInfo[$i]->PRODUCT_NAME,
                "PRODUCT_PRICE" => round($ContractInfo[$i]->PRODUCT_PRICE, 3)
            ]];
        }

        return $contract_arr;
    }
}