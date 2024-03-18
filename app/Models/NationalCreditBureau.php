<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NationalCreditBureau extends Model
{
    use HasFactory;

    protected $table = 'NationalCreditBureau';

    public $timestamps = false;
    
    protected $primaryKey = 'ID';

    protected $fillable = [
        "ID",
        "Family_Name_1",
        "Family_Name_2",
        "First_Name",
        "Middle",
        "Marital_Status",
        "Date_Of_Birth",
        "Gender",
        "Title_Prefix",
        "Nationality",
        "Number_Of_Children",
        "Spouse_Name",
        "Occupation",
        "Customer_Type_Field",
        "ID_Type",
        "ID_Number",
        "ID_Issue_Country",
        "Address_Line_1",
        "Address_Line_2",
        "Address_Line_3",
        "Sub_District",
        "District",
        "Province",
        "Country",
        "Postal_Code",
        "Telephone",
        "Telephone_Type",
        "Address_Type",
        "Residential_Status",
        "Current_New_Member_Code",
        "Current_New_Member_Name",
        "Current_New_Account_Number",
        "Account_Type",
        "Ownership_Indicator",
        "Currency_Code",
        "Future_Use",
        "Date_Account_Opened",
        "Date_Of_Last_Payment",
        "Date_Account_Closed",
        "As_Of_Date",
        "Credit_Limit_Original_Loan_Amount",
        "Amount_Owed_Credit_Use",
        "Amount_Past_Due",
        "Number_Of_Days_Past_Due_Delinquency_Status",
        "Old_Member_Code",
        "Old_Member_Name",
        "Old_Account_Number",
        "Default_Date",
        "Installment_Frequency",
        "Installment_Amount",
        "Installment_Number_Of_Payments",
        "Account_Status",
        "Loan_Object",
        "Collateral_1",
        "Collateral_2",
        "Collateral_3",
        "Date_Of_Last_Debt_Restructuring",
        "Percent_Payment",
        "Type_Of_Credit_Card",
        "Number_Of_Co_Borrower",
        "Unit_Make",
        "Unit_Model",
        "Credit_Limit_Type_Flag"
    ];


}
