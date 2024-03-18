<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ASSETS_INFORMATION_REF;


class ASSETS_INFORMATION extends Model
{
    protected $table = 'dbo.ASSETS_INFORMATION';

    use HasFactory;

    protected $fillable = [
        'ASSET_ID',
        'ASSETS_CATEGORY',
        'ASSETS_TYPE',
        'BRAND',
        'SERIES',
        'SUB_SERIES',
        'COLOR',
        'PRICE',
        'SERIALNUMBER',
        'MODELNUMBER',
        'DESCRIPTION',
        'IMAGE01',
        'IMAGE02',
        'IMAGE03',
        'IMAGE04',
        'IMAGE05',
        'IMAGE06',
        'EFFECTIVE_DATE',
        'END_DATE',
        'NOTICE_EMAIL',
        'NOTICE_MONTH',
        'REMARK',
        'STATUS_ID',
    ];

    public function assets_information_ref()
    {
        return $this->hasMany(ASSETS_INFORMATION_REF::class, 'ASSET_ID_REF', 'ASSET_ID');
    }

    public function scopeWithSeriesLike($query, $series)
    {
        return $query->select([
            'ASSET_ID', 'ASSETS_CATEGORY', 'ASSETS_TYPE', 'BRAND',
            'SERIES', 'SUB_SERIES', 'COLOR', 'PRICE', 'SERIALNUMBER',
            'MODELNUMBER', 'DESCRIPTION', 'BRAND'
        ])->where('MODELNUMBER', 'like', '%' . $series . '%');
    }
}
