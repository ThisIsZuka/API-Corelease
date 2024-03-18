<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SETUP_COMPANY_BRANCH extends Model
{
    protected $table = 'SETUP_COMPANY_BRANCH';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'COMP_BRANCH_ID',
        'COMPANY_CODE',
        'BRANCH_CODE',
        'BRANCH_TYPE',
        'BRANCH_NAME',
        'BRANCH_SHORT_NAME',
        'BRANCH_ADDRESS',
        'PHONE_01',
        'PHONE_02',
        'PHONE_03',
        'BRANCH_AD',
        'DEP_CODE',
        'BRANCH_EMAIL',
        'ACTIVE_STATUS',
    ];

    public static function getSetupcompanybranch($BRANCH_TYPE_ID, $TxtSearch)
    {
        return self::select('COMP_BRANCH_ID', 'COMPANY_CODE', 'BRANCH_CODE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_NAME', 'BRANCH_SHORT_NAME', 'BRANCH_ADDRESS', 'PHONE_01', 'PHONE_02', 'PHONE_03', 'BRANCH_AD', 'DEP_CODE', 'BRANCH_EMAIL', 'SETUP_COMPANY_BRANCH.ACTIVE_STATUS')
            ->leftJoin('MT_BRANCH_TYPE', 'SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', 'MT_BRANCH_TYPE.BRANCH_TYPE_ID')
            ->where('SETUP_COMPANY_BRANCH.ACTIVE_STATUS', '=', 'T')
            ->where('MT_BRANCH_TYPE.ACTIVE_STATUS', '=', 'T')
            ->where('SETUP_COMPANY_BRANCH.BRANCH_TYPE', '=', $BRANCH_TYPE_ID)
            ->where(function ($query) use ($TxtSearch) {
                $query->where('BRANCH_SHORT_NAME', 'LIKE', '%' . $TxtSearch . '%');
                $query->orWhere('BRANCH_ADDRESS', 'LIKE', '%' . $TxtSearch . '%');
            })
            ->get();
    }
}
