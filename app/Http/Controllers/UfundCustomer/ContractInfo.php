<?php
namespace App\Http\Controllers\UfundCustomer;

use App\Models\ContractModels;

class ContractInfo {
    function getContractInfo($contract_id) {
        $conn = new ContractModels;
        return response()->json($conn->search_contract($contract_id));
    }
}