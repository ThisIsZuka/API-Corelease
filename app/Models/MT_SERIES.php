<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MT_SERIES extends Model
{
    protected $table = 'MT_SERIES';

    use HasFactory;

    protected $fillable = [
        'SERIES_ID',
        'SERIES_CODE',
        'SERIES_NAME',
        'BRAND_ID',
        'BRAND_NAME',
        'ACTIVE_STATUS'
    ];

    public function seriesinfo()
    {
        return $this->hasMany(MT_SUB_SERIES::class, 'SERIES_ID');
    }

}
