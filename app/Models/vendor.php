<?php 

namespace App\Models;

use App\Models\Basemodels;

class vendor extends Basemodels {
    public CONST FIELD = [
        "VendorCode"
      ,["ComCode"]
      ,["VDType"]
      ,["VDFirstName"]
      ,['VDMiddleName']
      ,['VDLastName']
      ,['VDName']
      ,['VDSearchName']
      ,['VDLanguage']
      ,['VDGroupCode']
      ,['VDCouRG']
      ,['VDHouseNo']
      ,['VDBuilding']
      ,['VDVillage']
      ,['VDMoo']
      ,['VDSoi']
      ,['VDStreet']
      ,['VDSubDistrict']
      ,['VDDistrict']
      ,['VDState']
      ,['VDZipCode']
      ,['VDCountry']
      ,['VDCPhone']
      ,['VDCFax']
      ,['ContactName']
      ,['CTPosition']
      ,['CTPhone']
      ,['VDExtension']
      ,['CTFax']
      ,['CTEmail']
      ,['ResNum']
      ,['TaxAddTypeCode']
      ,['VDBRnumber']
      ,['TaxCode']
      ,['CalWHtax']
      ,['WHTaxGroupCode']
      ,['VDtypeWHCode']
      ,['PaytermCode']
      ,['VDMEPayment']
      ,['CurCode']
      ,['VDBankName']
      ,['VDBankAccount']
      ,['BankGroupCode']
      ,['TradeGroupID']
      ,['TradingCondition']
      ,['Active']
      ,['CreatedBy']
      ,['CreatedDatetime']
      ,['ModifiedBy']
      ,['ModifiedDatetime']
    ];

    function __construct()
    {
        parent::__construct('Purch_VendorMaster');
        return $this->getInstant('Purch_VendorMaster');
    }
}