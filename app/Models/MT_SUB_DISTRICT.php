<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT_SUB_DISTRICT extends Model
{
    protected $table = 'MT_SUB_DISTRICT';

    use HasFactory;
    protected $primaryKey = 'SUB_DISTRICT_ID';

    protected $fillable = [
        'SUB_DISTRICT_ID',
        'SUB_DISTRICT_NAME',
        'DISTRICT_ID',
    ];

    public function mt_district()
    {
        return $this->belongsTo(MT_DISTRICT::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }

    public function mt_post_code()
    {
        return $this->hasMany(MT_POST_CODE::class, 'SUB_DISTRICT_ID', 'SUB_DISTRICT_ID');
    }

    public static function getSubDistrictsWithPostCode($districtId)
    {
        return self::select('MT_SUB_DISTRICT.*', 'MT_POST_CODE.POST_CODE_ID as POST_CODE_ID')
            ->join('MT_POST_CODE', 'MT_POST_CODE.SUB_DISTRICT_ID', '=', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID')
            ->where('MT_SUB_DISTRICT.DISTRICT_ID', $districtId)
            ->get();
    }

    // public function getSubDistrictsWithPostCode()
    // {
    //     return $this->select('MT_SUB_DISTRICT.*', 'MT_POST_CODE.POST_CODE_ID')
    //         ->leftjoin('MT_POST_CODE', 'MT_POST_CODE.SUB_DISTRICT_ID', '=', 'MT_SUB_DISTRICT.SUB_DISTRICT_ID')
    //         ->where('MT_SUB_DISTRICT.DISTRICT_ID', $this->DISTRICT_ID)
    //         ->get();
    // }
}
