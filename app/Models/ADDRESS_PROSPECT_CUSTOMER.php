<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ADDRESS_PROSPECT_CUSTOMER extends Model
{
    protected $table = 'ADDRESS_PROSPECT_CUSTOMER';

    public $timestamps = false;

    use HasFactory;

    protected $primaryKey = 'ADD_CUST_ID';

    protected $fillable = [
        'ADD_CUST_ID',
        'QUOTATION_ID',
        'PST_CUST_ID',
        'A1_MASTER',
        'A1_COPY',
        'A1_NO',
        'A1_MOI',
        'A1_VILLAGE',
        'A1_BUILDING',
        'A1_FLOOR',
        'A1_ROOM_NO',
        'A1_SOI',
        'A1_ROAD',
        'A1_PROVINCE',
        'A1_DISTRICT',
        'A1_SUBDISTRICT',
        'A1_POSTALCODE',
        'A1_OWNER_TYPE',
        'A1_LIVEING_TIME',
        'A1_PHONE',
        'A1_LATITUDE',
        'A1_LONGITUDE',
        'A2_MASTER',
        'A2_COPY',
        'A2_NO',
        'A2_MOI',
        'A2_VILLAGE',
        'A2_BUILDING',
        'A2_FLOOR',
        'A2_ROOM_NO',
        'A2_SOI',
        'A2_ROAD',
        'A2_PROVINCE',
        'A2_DISTRICT',
        'A2_SUBDISTRICT',
        'A2_POSTALCODE',
        'A2_OWNER_TYPE',
        'A2_LIVEING_TIME',
        'A2_PHONE',
        'A2_LATITUDE',
        'A2_LONGITUDE',
        'A3_MASTER',
        'A3_COPY',
        'A3_NO',
        'A3_MOI',
        'A3_VILLAGE',
        'A3_BUILDING',
        'A3_FLOOR',
        'A3_ROOM_NO',
        'A3_SOI',
        'A3_ROAD',
        'A3_PROVINCE',
        'A3_DISTRICT',
        'A3_SUBDISTRICT',
        'A3_POSTALCODE',
        'A3_OWNER_TYPE',
        'A3_LIVEING_TIME',
        'A3_PHONE',
        'A3_LATITUDE',
        'A3_LONGITUDE',
        'A_MASTER_WORK',
        'A_COPY_WORK',
        'A_NO_WORK',
        'A_MOI_WORK',
        'A_VILLAGE_WORK',
        'A_BUILDING_WORK',
        'A_FLOOR_WORK',
        'A_ROOM_NO_WORK',
        'A_SOI_WORK',
        'A_ROAD_WORK',
        'A_PROVINCE_WORK',
        'A_DISTRICT_WORK',
        'A_SUBDISTRICT_WORK',
        'A_POSTALCODE_WORK',
        'A_OWNER_TYPE_WORK',
        'A_LIVEING_TIME_WORK',
        'A_PHONE_WORK',
        'A_LATITUDE_WORK',
        'A_LONGITUDE_WORK',
        'A_NAME_WORK',
    ];
}
