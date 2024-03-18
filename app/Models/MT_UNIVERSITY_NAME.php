<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MT_UNIVERSITY_NAME extends Model
{
    use HasFactory;

    protected $table = 'MT_UNIVERSITY_NAME';
    protected $primaryKey = 'MT_UNIVERSITY_ID';

    protected $fillable = [
        'MT_UNIVERSITY_ID',
        'UNIVERSITY_CODE',
        'UNIVERSITY_NAME',
        'PROVINCE_ID',
        'DISTRICT_ID',
        'ZONE_ENG',
        'EDU_TYPE',
        'ACTIVE_STATUS',
    ];


    public function PROVINCE()
    {
        return $this->belongsTo(MT_PROVINCE::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }

    public function DISTRICT()
    {
        return $this->belongsTo(MT_DISTRICT::class, 'DISTRICT_ID', 'DISTRICT_ID');
    }

    public function FACULTY()
    {
        return $this->hasMany(MT_FACULTY::class, 'MT_UNIVERSITY_ID', 'MT_UNIVERSITY_ID');
    }

    public static function searchUniversity($params)
    {
        $query = DB::table('dbo.MT_UNIVERSITY_NAME')->select('MT_UNIVERSITY_ID', 'UNIVERSITY_CODE', 'UNIVERSITY_NAME', 'PROVINCE_ID', 'DISTRICT_ID');

        if (!empty($params['PROVINCE_ID']) && preg_match('/^\d+$/', $params['PROVINCE_ID'])) {
            $query->where('PROVINCE_ID', $params['PROVINCE_ID']);
        }

        if (isset($params['DISTRICT_ID']) && preg_match('/^\d+$/', $params['DISTRICT_ID'])) {
            $query->where('DISTRICT_ID', $params['DISTRICT_ID']);
        }

        if (!empty($params['U_Search'])) {
            $query->where('UNIVERSITY_NAME', 'LIKE', '%' . $params['U_Search'] . '%');
        }

        $query->where('MT_UNIVERSITY_ID', '!=', '0');

        return $query->get();
    }
}
