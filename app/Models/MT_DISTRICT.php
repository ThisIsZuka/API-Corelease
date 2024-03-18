<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_DISTRICT extends Model
{
    protected $table = 'MT_DISTRICT';

    use HasFactory;

    protected $fillable = [
        'DISTRICT_ID',
        'DISTRICT_NAME',
        'PROVINCE_ID'
    ];

    public function mt_province()
    {
        return $this->belongsTo(MT_PROVINCE::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }

    public function mt_sub_district()
    {
        return $this->hasMany(MT_SUB_DISTRICT::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }

    public static function GetDistrictWithProvinceID($id)
    {
        return self::select('*')
            ->leftJoin('MT_PROVINCE', 'MT_DISTRICT.PROVINCE_ID', '=', 'MT_PROVINCE.PROVINCE_ID')
            ->where('MT_DISTRICT.PROVINCE_ID', $id)
            ->get();
    }
}
