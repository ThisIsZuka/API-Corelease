<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT_POST_CODE extends Model
{
    protected $table = 'MT_POST_CODE';

    use HasFactory;
    protected $primaryKey = 'POST_CODE_ID';

    protected $fillable = [
        'POST_CODE_ID',
        'SUB_DISTRICT_ID',
        'SUB_DISTRICT_NAME',
    ];


    public function MT_SUB_DISTRICT()
    {
        return $this->belongsTo(MT_SUB_DISTRICT::class, 'SUB_DISTRICT_ID', 'SUB_DISTRICT_ID');
    }

    public static function CheckMTPostCode($PROVINCE, $DISTRICT, $SUBDISTRICT, $POSTALCODE)
    {
        return MT_POST_CODE::with(['MT_SUB_DISTRICT', 'MT_SUB_DISTRICT.MT_DISTRICT', 'MT_SUB_DISTRICT.MT_DISTRICT.MT_PROVINCE'])
            ->join('MT_SUB_DISTRICT', 'MT_POST_CODE.SUB_DISTRICT_ID', '=', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID')
            ->join('MT_DISTRICT', 'MT_SUB_DISTRICT.DISTRICT_ID', '=', 'MT_DISTRICT.DISTRICT_ID')
            ->join('MT_PROVINCE', 'MT_DISTRICT.PROVINCE_ID', '=', 'MT_PROVINCE.PROVINCE_ID')
            ->where('MT_PROVINCE.PROVINCE_ID', $PROVINCE)
            ->where('MT_DISTRICT.DISTRICT_ID', $DISTRICT)
            ->where('MT_SUB_DISTRICT.SUB_DISTRICT_ID', $SUBDISTRICT)
            ->where('MT_POST_CODE.POST_CODE_ID', $POSTALCODE)
            ->select('MT_PROVINCE.PROVINCE_NAME', 'MT_DISTRICT.DISTRICT_NAME', 'MT_SUB_DISTRICT.SUB_DISTRICT_NAME', 'MT_POST_CODE.*')
            ->get();
    }
}
