<?php
namespace App\Http\Controllers\UfundCustomer;

use App\Models\ContractModels;

class ContractInfo {
    function getContractInfo($contract_id, ContractModels $models) {
        $conn = $models;
        $contractInfo = $conn->search_contract($contract_id);

        if (!empty($contractInfo)) {
            return response()->json($contractInfo);
        } else {
            return response()->json([
                [
                    "code" => "-1"
                    ,"message" => "Contract: " . $contract_id . " not found." 
                ]
            ]);
        }
    }
}