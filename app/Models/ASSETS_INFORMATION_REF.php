<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ASSETS_INFORMATION_REF extends Model
{
    protected $table = 'dbo.ASSETS_INFORMATION_REF';

    use HasFactory;

    protected $fillable = [
        'ID',
        'SERIES_CODE',
        'ASSET_ID_REF',
    ];

    public function assetsInformation()
    {
        return $this->belongsTo(ASSETS_INFORMATION::class, 'ASSET_ID', 'ASSET_ID_REF');
    }


    public static function findWithAssetsInformation($id)
    {
        // return self::with('assetsInformation')->find($id);
        return ASSETS_INFORMATION_REF::select('ASSETS_INFORMATION_REF.*','ASSETS_INFORMATION.*')
            ->leftJoin('ASSETS_INFORMATION', 'ASSETS_INFORMATION_REF.ASSET_ID_REF', '=', 'ASSETS_INFORMATION.ASSET_ID')
            ->where('ASSETS_INFORMATION_REF.ID', '=', $id)
            ->get();
    }
}
